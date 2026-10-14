<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;
use Inertia\Inertia;
use Inertia\Response;

class BillingController extends Controller
{
    public function index(): Response
    {
        $subscriptions = Subscription::with(['organization', 'plan'])->latest()->limit(50)->get();
        $openInvoices = Invoice::whereIn('status', ['pending', 'overdue', 'open'])->count();
        $paidTotal = Invoice::where('status', 'paid')->where('paid_at', '>=', now()->subDays(30))->sum('total');

        return Inertia::render('Admin/Billing/Index', [
            'subscriptions' => $subscriptions,
            'openInvoices' => $openInvoices,
            'paidTotal' => $paidTotal,
        ]);
    }
}
