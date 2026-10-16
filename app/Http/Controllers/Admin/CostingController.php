<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminAnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CostingController extends Controller
{
    public function __construct(
        private AdminAnalyticsService $analyticsService
    ) {}

    /**
     * Display AI usage costing and analytics dashboard
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['date_from', 'date_to', 'organization_id']);
        $costing = $this->analyticsService->getAiUsageCosting($filters);
        $platformAnalytics = $this->analyticsService->getPlatformAnalytics($filters);
        $systemHealth = $this->analyticsService->getSystemHealth();

        return Inertia::render('Admin/Costing/Index', [
            'costing' => $costing,
            'platformAnalytics' => $platformAnalytics,
            'systemHealth' => $systemHealth,
            'filters' => $filters,
        ]);
    }
}
