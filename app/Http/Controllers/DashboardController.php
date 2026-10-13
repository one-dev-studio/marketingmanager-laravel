<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardAnalyticsService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardAnalyticsService $analyticsService
    ) {}

    public function index(Request $request, string $organizationId): Response|JsonResponse
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        $kpis = $this->analyticsService->getKPIs((int) $organizationId, $startDate, $endDate);
        $campaignPerformance = $this->analyticsService->getCampaignPerformance((int) $organizationId, $startDate, $endDate);
        $contentCalendar = $this->analyticsService->getContentCalendarPreview((int) $organizationId, 7);
        $pendingTasks = $this->analyticsService->getPendingTasks((int) $organizationId, 10);
        $activityFeed = $this->analyticsService->getActivityFeed((int) $organizationId, 20);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'kpis' => $kpis,
                    'campaign_performance' => $campaignPerformance,
                    'content_calendar' => $contentCalendar,
                    'pending_tasks' => $pendingTasks,
                    'activity_feed' => $activityFeed,
                ],
            ]);
        }

        $user = $request->user();

        return Inertia::render('Dashboard/Index', [
            'organizationId' => $organizationId,
            'userName' => $user !== null ? ($user->name ?? 'User') : 'User',
        ]);
    }

    public function getKPIs(Request $request, string $organizationId): JsonResponse
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : null;
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : null;

        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getKPIs((int) $organizationId, $startDate, $endDate),
        ]);
    }

    public function getActivityFeed(Request $request, string $organizationId): JsonResponse
    {
        $limit = $request->get('limit', 20);

        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getActivityFeed((int) $organizationId, (int) $limit),
        ]);
    }

    public function getPendingTasks(Request $request, string $organizationId): JsonResponse
    {
        $limit = $request->get('limit', 10);

        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getPendingTasks((int) $organizationId, (int) $limit),
        ]);
    }

    public function getContentCalendar(Request $request, string $organizationId): JsonResponse
    {
        $days = $request->get('days', 7);

        return response()->json([
            'success' => true,
            'data' => $this->analyticsService->getContentCalendarPreview((int) $organizationId, (int) $days),
        ]);
    }
}
