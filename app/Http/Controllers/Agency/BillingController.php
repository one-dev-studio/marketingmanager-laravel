<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\AgencyBillingInertiaResource;
use App\Jobs\SendInvoiceReminders;
use App\Models\Agency;
use App\Models\Invoice;
use App\Services\AgencyService;
use App\Services\InvoiceService;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Agency Billing Controller
 * Handles billing and invoicing for agency clients
 * Requires agency admin access
 */
class BillingController extends Controller
{
    public function __construct(
        private AgencyService $agencyService,
        private InvoiceService $invoiceService,
        private PdfService $pdfService
    ) {}

    /**
     * Display billing dashboard with invoice summary
     */
    public function index(Request $request, Agency $agency): Response
    {
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);

        $filters = [
            'status' => $request->get('status'),
            'per_page' => $request->get('per_page', 15),
        ];

        $invoices = $this->invoiceService->getInvoicesForOrganizations($clientOrganizationIds, $filters);
        $summary = $this->invoiceService->calculateSummary($clientOrganizationIds);

        return Inertia::render('Agency/Billing/Index', [
            'agencyId' => (string) $agency->id,
            'summary' => $summary,
            'invoices' => $invoices->through(
                fn (Invoice $invoice) => AgencyBillingInertiaResource::invoiceListItem($invoice, $agency)
            ),
            'remindersAction' => route('agency.billing.send-reminders', ['agency' => $agency]),
        ]);
    }

    /**
     * Mark invoice as paid
     */
    public function pay(Request $request, Agency $agency, Invoice $invoice)
    {
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);

        if (! in_array($invoice->organization_id, $clientOrganizationIds)) {
            abort(403, 'Invoice does not belong to agency clients.');
        }

        $this->invoiceService->markAsPaid($invoice);

        return redirect()->route('agency.billing.index', ['agency' => $agency])
            ->with('success', 'Invoice marked as paid successfully.');
    }

    /**
     * Download invoice PDF
     */
    public function download(Agency $agency, Invoice $invoice)
    {
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);

        if (! in_array($invoice->organization_id, $clientOrganizationIds)) {
            abort(403, 'Invoice does not belong to agency clients.');
        }

        return $this->pdfService->downloadInvoicePdf($invoice);
    }

    /**
     * Send invoice reminders
     */
    public function sendReminders(Request $request, Agency $agency)
    {
        $clientOrganizationIds = $this->agencyService->getClientOrganizationIds($agency);

        SendInvoiceReminders::dispatch($clientOrganizationIds);

        return redirect()->route('agency.billing.index', ['agency' => $agency])
            ->with('success', 'Invoice reminders are being processed.');
    }
}
