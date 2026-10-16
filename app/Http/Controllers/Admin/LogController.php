<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PlatformSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LogController extends Controller
{
    public function __construct(
        private PlatformSettingsService $settingsService
    ) {}

    /**
     * Display system logs
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['level', 'user_id', 'date_from', 'date_to']);
        $logs = $this->settingsService->getSystemLogs($filters);
        $performanceMetrics = $this->settingsService->getPerformanceMetrics();

        return Inertia::render('Admin/Logs/Index', [
            'logs' => $logs->through(fn ($log) => [
                'level' => $log->level ?? null,
                'message' => $log->message ?? null,
                'created_at' => $log->created_at?->format('M j, Y H:i'),
            ]),
            'filters' => $filters,
            'performanceMetrics' => $performanceMetrics,
        ]);
    }
}
