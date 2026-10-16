<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            $table->index(['organization_id', 'status', 'scheduled_at'], 'scheduled_posts_org_status_scheduled_idx');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->index(['organization_id', 'status', 'due_date'], 'tasks_org_status_due_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'type', 'created_at'], 'notifications_user_type_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            $table->dropIndex('scheduled_posts_org_status_scheduled_idx');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropIndex('tasks_org_status_due_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_type_created_idx');
        });
    }
};
