<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Channel;
use App\Models\ContentApproval;
use App\Models\ScheduledPost;
use Tests\TestCase;

class ContentWorkflowInertiaTest extends TestCase
{
    public function test_content_calendar_index_renders_inertia_with_events(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $campaign = Campaign::factory()->create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
        ]);

        ScheduledPost::factory()->create([
            'organization_id' => $organization->id,
            'campaign_id' => $campaign->id,
            'created_by' => $user->id,
            'scheduled_at' => now()->addDays(2),
            'content' => 'Calendar post body',
        ]);

        $response = $this->get(route('main.content-calendar.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('ContentCalendar/Index')
            ->has('events', 1)
            ->has('range.start')
            ->has('sidebar'));
    }

    public function test_content_approvals_index_renders_inertia_list(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $campaign = Campaign::factory()->create([
            'organization_id' => $organization->id,
            'created_by' => $user->id,
        ]);

        $channel = Channel::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'active',
        ]);

        $post = ScheduledPost::factory()->create([
            'organization_id' => $organization->id,
            'campaign_id' => $campaign->id,
            'channel_id' => $channel->id,
            'created_by' => $user->id,
            'status' => 'pending',
            'content' => 'Needs approval',
        ]);

        ContentApproval::query()->create([
            'scheduled_post_id' => $post->id,
            'organization_id' => $organization->id,
            'requested_by' => $user->id,
            'approved_by' => $user->id,
            'status' => ContentApproval::STATUS_PENDING,
            'requested_at' => now(),
        ]);

        $response = $this->get(route('main.content-approvals.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('ContentApprovals/Index')
            ->has('approvals.data', 1)
            ->where('approvals.data.0.status', ContentApproval::STATUS_PENDING)
            ->has('sidebar'));
    }
}
