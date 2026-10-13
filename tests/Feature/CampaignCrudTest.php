<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Channel;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->user, $this->organization] = $this->actingAsOrganizationAdmin();
    }

    public function test_user_can_create_campaign(): void
    {
        $channel = Channel::factory()->create([
            'organization_id' => $this->organization->id,
            'status' => 'active',
        ]);

        $response = $this->post("/main/{$this->organization->id}/campaigns", [
            'name' => 'New Campaign',
            'description' => 'Campaign Description',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'budget' => 500,
            'channels' => [
                ['id' => $channel->id, 'budget' => 500],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('campaigns', [
            'name' => 'New Campaign',
            'organization_id' => $this->organization->id,
        ]);
    }

    public function test_user_can_view_campaigns(): void
    {
        Campaign::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/campaigns");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Campaigns/Index')->has('campaigns.data', 3));
    }

    public function test_user_can_view_single_campaign(): void
    {
        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/campaigns/{$campaign->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Campaigns/Show')
            ->where('campaign.id', $campaign->id)
            ->has('sidebar')
            ->has('appShell'));
    }

    public function test_user_can_update_campaign(): void
    {
        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->put("/main/{$this->organization->id}/campaigns/{$campaign->id}", [
            'name' => 'Updated Campaign Name',
            'description' => $campaign->description,
            'status' => $campaign->status,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('campaigns', [
            'id' => $campaign->id,
            'name' => 'Updated Campaign Name',
        ]);
    }

    public function test_user_can_delete_campaign(): void
    {
        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->delete("/main/{$this->organization->id}/campaigns/{$campaign->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('campaigns', [
            'id' => $campaign->id,
        ]);
    }

    public function test_user_cannot_access_other_organization_campaigns(): void
    {
        $otherOrganization = Organization::factory()->create();
        $campaign = Campaign::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/campaigns/{$campaign->id}");

        $this->assertTrue(in_array($response->status(), [403, 404], true));
    }
}
