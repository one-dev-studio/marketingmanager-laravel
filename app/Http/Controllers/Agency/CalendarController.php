<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Campaign;
use App\Models\ScheduledPost;
use App\Services\AgencyService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Agency Calendar Controller
 * Handles aggregated calendar view across all client organizations
 */
class CalendarController extends Controller
{
    public function __construct(
        private AgencyService $agencyService
    ) {}

    /**
     * Display aggregated calendar view
     */
    public function index(Request $request, Agency $agency): Response
    {
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);

        $startDate = $request->get('start', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end', now()->endOfMonth()->toDateString());

        $scheduledPosts = ScheduledPost::whereIn('organization_id', $clientOrganizationIds)
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->with(['organization', 'campaign', 'channels'])
            ->get()
            ->map(fn (ScheduledPost $post) => [
                'id' => $post->id,
                'organization_id' => $post->organization_id,
                'organization_name' => $post->organization?->name,
                'title' => $post->title ?? $post->campaign?->name ?? 'Scheduled post',
                'scheduled_at' => (string) $post->scheduled_at,
            ])
            ->values()
            ->all();

        $campaigns = Campaign::whereIn('organization_id', $clientOrganizationIds)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->with('organization')
            ->get()
            ->map(fn (Campaign $campaign) => [
                'id' => $campaign->id,
                'organization_id' => $campaign->organization_id,
                'organization_name' => $campaign->organization?->name,
                'name' => $campaign->name,
                'start_date' => (string) $campaign->start_date,
            ])
            ->values()
            ->all();

        $clients = $agency->clientOrganizations()
            ->orderBy('name')
            ->get(['organizations.id', 'organizations.name'])
            ->map(fn ($org) => ['id' => $org->id, 'name' => $org->name])
            ->values()
            ->all();

        return Inertia::render('Agency/Calendar/Index', [
            'agencyId' => (string) $agency->id,
            'clients' => $clients,
            'scheduledPosts' => $scheduledPosts,
            'campaigns' => $campaigns,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
