<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;

class BillingController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['organization', 'plan'])->latest()->limit(50)->get();
        $openInvoices = Invoice::whereIn('status', ['pending', 'overdue', 'open'])->count();
        $paidTotal = Invoice::where('status', 'paid')->where('paid_at', '>=', now()->subDays(30))->sum('total');

        return view('admin.billing.index', compact('subscriptions', 'openInvoices', 'paidTotal'));
    }
}
