<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\ContentApproval;
use App\Models\ScheduledPost;
use App\Models\Task;
use Tests\TestCase;

class TasksCollaborationReviewInertiaTest extends TestCase
{
    public function test_tasks_index_renders_inertia_kanban(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        Task::query()->create([
            'organization_id' => $organization->id,
            'title' => 'Kanban Task',
            'status' => 'todo',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('main.tasks.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Tasks/Index')
            ->has('tasks', 1)
            ->where('tasks.0.title', 'Kanban Task')
            ->has('members')
            ->has('sidebar'));
    }

    public function test_collaboration_index_renders_inertia_hub(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.collaboration.index', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Collaboration/Index')
                ->has('topics')
                ->has('pendingReviews')
                ->has('recentActivity')
                ->has('sidebar'));
    }

    public function test_content_approval_show_renders_inertia_detail(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $scheduledPost = ScheduledPost::factory()->create([
            'organization_id' => $organization->id,
            'content' => 'Post body for review',
        ]);

        $approval = ContentApproval::query()->create([
            'organization_id' => $organization->id,
            'scheduled_post_id' => $scheduledPost->id,
            'requested_by' => $user->id,
            'status' => ContentApproval::STATUS_PENDING,
            'requested_at' => now(),
        ]);

        $this->get(route('main.content-approvals.show', [
            'organizationId' => $organization->id,
            'approval' => $approval->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('ContentApprovals/Show')
                ->where('approval.content', 'Post body for review')
                ->has('approval.history')
                ->has('sidebar'));
    }
}
