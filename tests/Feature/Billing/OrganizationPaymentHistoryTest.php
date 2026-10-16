<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\Organization;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationPaymentHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_org_admin_can_list_payment_history_json(): void
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()->attach($organization->id, ['role_id' => 1]);

        Payment::factory()->count(2)->create([
            'organization_id' => $organization->id,
            'status' => 'completed',
        ]);

        $this->actingAs($user)
            ->getJson(route('main.billing.payments', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data.data');
    }
}
