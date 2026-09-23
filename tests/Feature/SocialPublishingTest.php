<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Channel;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SocialPublishingTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->user, $this->organization] = $this->actingAsOrganizationAdmin();
    }

    public function testUserCanSchedulePost(): void
    {
        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
            'created_by' => $this->user->id,
        ]);

        $channel = Channel::factory()->create([
            'organization_id' => $this->organization->id,
            'platform' => 'facebook',
            'status' => 'active',
        ]);

        $response = $this->postJson("/main/{$this->organization->id}/campaigns/{$campaign->id}/content", [
            'channel_id' => $channel->id,
            'content' => 'Test post content',
            'scheduled_at' => now()->addDay()->toDateTimeString(),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('scheduled_posts', [
            'channel_id' => $channel->id,
            'content' => 'Test post content',
        ]);
    }

    public function testSocialPublishingRouteIsRegistered(): void
    {
        $this->assertTrue(Route::has('main.social.publishing.publish'));
    }
}
