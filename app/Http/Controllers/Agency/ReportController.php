<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateAgencyReport;
use App\Models\Agency;
use App\Models\Organization;
use App\Models\Report;
use App\Services\AgencyReportService;
use App\Services\AgencyService;
use App\Services\PdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Agency Report Controller
 * Handles report generation for agency clients
 */
class ReportController extends Controller
{
    public function __construct(
        private AgencyService $agencyService,
        private AgencyReportService $reportService,
        private PdfService $pdfService
    ) {}

    /**
     * Display report generation interface
     */
    public function index(Request $request, Agency $agency): Response
    {
        $clients = $agency->clientOrganizations()
            ->orderBy('name')
            ->get();

        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);

        $reports = Report::whereIn('organization_id', $clientOrganizationIds)
            ->where('type', 'like', 'agency_%')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->through(fn (Report $report) => [
                'id' => $report->id,
                'name' => $report->name ?? $report->type,
                'status' => $report->status,
                'created_at' => $report->created_at?->toIso8601String(),
                'summary' => is_array($report->data) ? ($report->data['executive_summary'] ?? null) : null,
            ]);

        return Inertia::render('Agency/Reports/Index', [
            'agencyId' => (string) $agency->id,
            'clients' => $clients->map(fn (Organization $client) => [
                'id' => $client->id,
                'name' => $client->name,
            ])->values()->all(),
            'reports' => $reports,
        ]);
    }

    /**
     * Generate report for a client
     */
    public function generate(Request $request, Agency $agency): RedirectResponse
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'report_type' => 'required|in:weekly,monthly,quarterly',
        ]);

        $organization = Organization::findOrFail($request->organization_id);

        // Verify organization belongs to agency
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);
        if (! in_array($organization->id, $clientOrganizationIds)) {
            abort(403, 'Organization does not belong to agency clients.');
        }

        // Dispatch job for async processing
        GenerateAgencyReport::dispatch($agency, $organization, $request->report_type);

        return redirect()->route('agency.reports.index', ['agency' => $agency])
            ->with('success', 'Report generation started. It will be available shortly.');
    }

    /**
     * Download report PDF
     */
    public function download(Agency $agency, Report $report)
    {
        // Verify report belongs to agency client
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);
        if (! in_array($report->organization_id, $clientOrganizationIds)) {
            abort(403, 'Report does not belong to agency clients.');
        }

        $filename = "report-{$report->id}-{$report->created_at->format('Y-m-d')}.pdf";

        return $this->pdfService->downloadReportPdf($report->data, $filename);
    }
}
