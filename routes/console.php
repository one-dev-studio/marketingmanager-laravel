<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Social Media Integration scheduled tasks
Schedule::hourly()->group(function () {
    Schedule::command('social:monitor-connections');
    Schedule::command('social:tokens-refresh');
});

// Publishing scheduled tasks
Schedule::everyMinute()->group(function () {
    Schedule::call(function () {
        $scheduledPosts = \App\Models\ScheduledPost::where('status', 'pending')
            ->where('scheduled_at', '<=', now())
            ->with('socialConnections')
            ->get();

        foreach ($scheduledPosts as $post) {
            foreach ($post->socialConnections as $connection) {
                \App\Jobs\PublishScheduledPost::dispatch($post, $connection);
            }
        }
    })->name('publish-scheduled-posts');
});

// Email Campaign scheduled tasks
Schedule::everyFiveMinutes()->group(function () {
    Schedule::call(function () {
        \App\Jobs\ProcessEmailCampaignQueue::dispatch();
    })->name('process-email-campaigns');
});

// Report Generation scheduled tasks
Schedule::dailyAt('08:00')->group(function () {
    Schedule::call(function () {
        $schedules = \App\Models\ReportSchedule::where('next_run_at', '<=', now())
            ->with(['report.organization', 'report.creator'])
            ->get();

        foreach ($schedules as $schedule) {
            $report = $schedule->report;
            $organization = $report->organization;
            $user = $report->creator;

            if ($organization && $user) {
                \App\Jobs\GenerateReport::dispatch($report, $organization, $user);
            }

            // Update next run time based on frequency
            $nextRunAt = match ($schedule->frequency) {
                'daily' => \Carbon\Carbon::now()->addDay()->startOfDay(),
                'weekly' => \Carbon\Carbon::now()->addWeek()->startOfWeek(),
                'monthly' => \Carbon\Carbon::now()->addMonth()->startOfMonth(),
                default => \Carbon\Carbon::now()->addDay(),
            };

            $schedule->update([
                'next_run_at' => $nextRunAt,
                'last_run_at' => now(),
            ]);
        }
    })->name('generate-scheduled-reports');
});

// Competitor Monitoring scheduled tasks
Schedule::dailyAt('06:00')->group(function () {
    Schedule::call(function () {
        \App\Jobs\MonitorCompetitors::dispatch();
    })->name('monitor-competitors');
});

// Agency Billing scheduled tasks
Schedule::dailyAt('09:00')->group(function () {
    Schedule::call(function () {
        $agencies = \App\Models\Agency::all();
        foreach ($agencies as $agency) {
            $clientOrganizationIds = app(\App\Services\AgencyService::class)
                ->getClientOrganizationIds($agency);

            if (! empty($clientOrganizationIds)) {
                \App\Jobs\SendInvoiceReminders::dispatch($clientOrganizationIds);
            }
        }
    })->name('invoice-reminders');
});
