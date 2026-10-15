<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Phase18PerformanceIndexesTest extends TestCase
{
    public function test_phase_18_hot_path_indexes_exist(): void
    {
        $scheduledIndexes = collect(Schema::getIndexes('scheduled_posts'))->pluck('name');
        $taskIndexes = collect(Schema::getIndexes('tasks'))->pluck('name');
        $notificationIndexes = collect(Schema::getIndexes('notifications'))->pluck('name');

        $this->assertTrue(
            $scheduledIndexes->contains('scheduled_posts_org_status_scheduled_idx'),
            'scheduled_posts org/status/scheduled_at index missing',
        );
        $this->assertTrue(
            $taskIndexes->contains('tasks_org_status_due_idx'),
            'tasks org/status/due_date index missing',
        );
        $this->assertTrue(
            $notificationIndexes->contains('notifications_user_type_created_idx'),
            'notifications user/type/created_at index missing',
        );
    }
}
