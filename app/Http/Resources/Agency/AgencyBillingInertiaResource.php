<?php

declare(strict_types=1);

namespace App\Http\Resources\Agency;

use App\Models\Agency;
use App\Models\Invoice;

final class AgencyBillingInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function invoiceListItem(Invoice $invoice, Agency $agency): array
    {
        return [
            'id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'organization_name' => $invoice->organization?->name,
            'total' => $invoice->total,
            'status' => $invoice->status,
            'due_date' => $invoice->due_date?->toDateString(),
            'download_href' => route('agency.billing.download', ['agency' => $agency, 'invoice' => $invoice]),
            'can_mark_paid' => $invoice->status !== 'paid',
            'pay_action' => route('agency.billing.pay', ['agency' => $agency, 'invoice' => $invoice]),
        ];
    }
}
