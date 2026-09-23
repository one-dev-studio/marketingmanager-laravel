<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Channel;
use App\Models\Organization;
use App\Models\ScheduledPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentCreationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organization $organization;
    private Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->user, $this->organization] = $this->actingAsOrganizationAdmin();
        $this->brand = Brand::factory()->create(['organization_id' => $this->organization->id]);
    }

    public function testUserCanCreateContent(): void
    {
        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
            'brand_id' => $this->brand->id,
            'created_by' => $this->user->id,
        ]);

        $channel = Channel::factory()->create([
            'organization_id' => $this->organization->id,
            'status' => 'active',
        ]);

        $response = $this->postJson("/main/{$this->organization->id}/campaigns/{$campaign->id}/content", [
            'channel_id' => $channel->id,
            'content' => 'Test Content',
            'scheduled_at' => now()->addDay()->toDateTimeString(),
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('scheduled_posts', [
            'content' => 'Test Content',
            'campaign_id' => $campaign->id,
        ]);
    }

    public function testUserCanUpdateContent(): void
    {
        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
            'created_by' => $this->user->id,
        ]);

        $post = ScheduledPost::factory()->create([
            'organization_id' => $this->organization->id,
            'campaign_id' => $campaign->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->putJson(
            "/main/{$this->organization->id}/campaigns/{$campaign->id}/content/{$post->id}",
            ['content' => 'Updated Content'],
        );

        $response->assertOk();
        $this->assertDatabaseHas('scheduled_posts', [
            'id' => $post->id,
            'content' => 'Updated Content',
        ]);
    }

    public function testUserCanSubmitContentForApproval(): void
    {
        $approver = User::factory()->create();
        $this->organization->users()->attach($approver->id, [
            'role_id' => \App\Models\Role::where('name', 'admin')->firstOrFail()->id,
        ]);

        $campaign = Campaign::factory()->create([
            'organization_id' => $this->organization->id,
            'created_by' => $this->user->id,
        ]);

        $post = ScheduledPost::factory()->create([
            'organization_id' => $this->organization->id,
            'campaign_id' => $campaign->id,
            'created_by' => $this->user->id,
            'status' => 'draft',
        ]);

        $response = $this->postJson(
            "/main/{$this->organization->id}/content-approvals/{$post->id}/request",
            ['approved_by' => $approver->id],
        );

        $response->assertCreated();
        $this->assertDatabaseHas('scheduled_posts', [
            'id' => $post->id,
            'status' => 'pending',
        ]);
    }
}
