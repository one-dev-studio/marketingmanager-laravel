<?php

namespace Tests\Feature\Campaign;

use App\Models\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CampaignCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_campaign(): void
    {
        [$user, $organization] = $this->createOrganizationAdmin();
        $channel = Channel::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'active',
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/campaigns', [
            'name' => 'Test Campaign',
            'start_date' => now()->addDay()->toDateString(),
            'budget' => 1000,
            'channels' => [
                ['id' => $channel->id, 'budget' => 1000],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'status',
                ],
            ]);

        $this->assertDatabaseHas('campaigns', [
            'name' => 'Test Campaign',
            'organization_id' => $organization->id,
        ]);
    }
}
