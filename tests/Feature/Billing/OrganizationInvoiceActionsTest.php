<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationInvoiceActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_org_admin_can_download_invoice_pdf_for_own_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()->attach($organization->id, ['role_id' => 1]);

        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $this->actingAs($user)
            ->get(route('main.billing.invoices.download', [
                'organizationId' => $organization->id,
                'invoice' => $invoice->id,
            ]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_org_admin_cannot_download_invoice_from_another_organization(): void
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $other = Organization::factory()->create();
        $user->organizations()->attach($organization->id, ['role_id' => 1]);

        $invoice = Invoice::factory()->create([
            'organization_id' => $other->id,
        ]);

        $this->actingAs($user)
            ->get(route('main.billing.invoices.download', [
                'organizationId' => $organization->id,
                'invoice' => $invoice->id,
            ]))
            ->assertNotFound();
    }

    public function test_org_admin_can_mark_pending_invoice_as_paid(): void
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $user->organizations()->attach($organization->id, ['role_id' => 1]);

        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->post(route('main.billing.invoices.pay', [
                'organizationId' => $organization->id,
                'invoice' => $invoice->id,
            ]), [
                'payment_method' => 'stripe',
                'payment_id' => 'pi_test_123',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'transaction_id' => 'pi_test_123',
        ]);
    }
}
