@extends('layouts.app')
@section('page-title', 'Billing')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Billing</h1>
    @foreach($alerts as $alert)
        <div class="rounded-md px-4 py-3 text-sm {{ $alert['type']==='danger' ? 'bg-red-50 text-red-800' : ($alert['type']==='warning' ? 'bg-yellow-50 text-yellow-800' : 'bg-blue-50 text-blue-800') }}">{{ $alert['message'] }}</div>
    @endforeach
    <div class="bg-white border rounded-lg p-6">
        <h2 class="font-medium">Current plan</h2>
        <p class="text-sm text-gray-600">{{ $subscription?->plan?->name ?? 'None' }} · {{ $subscription?->status ?? 'inactive' }}
            @if($subscription?->trial_ends_at) · trial until {{ $subscription->trial_ends_at->toFormattedDateString() }} @endif
        </p>
        <p class="text-xs text-gray-400 mt-2">Gateways: Stripe {{ $gateways['stripe'] ? 'configured' : 'needs STRIPE_SECRET' }} · PayPal {{ $gateways['paypal'] ? 'configured' : 'needs PAYPAL_CLIENT_ID' }}</p>
    </div>
    <div class="grid md:grid-cols-3 gap-4">
        @foreach($plans as $plan)
            <form method="POST" action="{{ $subscription ? route('main.billing.subscription.upgrade', ['organizationId' => $organizationId]) : route('main.billing.subscription.create', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4">
                @csrf
                @if($subscription) @method('PUT') @endif
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <h3 class="font-medium">{{ $plan->name }}</h3>
                <p class="text-sm">${{ $plan->price }} / {{ $plan->billing_cycle }}</p>
                <button class="mt-3 text-blue-700 text-sm">{{ $subscription ? 'Switch' : 'Subscribe' }}</button>
            </form>
        @endforeach
    </div>
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-medium mb-2">Usage ({{ $usageStats['period'] }})</h2>
        <p class="text-sm">AI tokens {{ $usageStats['ai_usage']['total_tokens'] }} · cost ${{ $usageStats['ai_usage']['total_cost'] }}</p>
    </div>
    <div class="bg-white border rounded-lg overflow-hidden">
        <h2 class="font-medium p-4">Invoices</h2>
        @foreach($invoices as $invoice)
            <div class="px-4 py-3 border-t flex justify-between text-sm">
                <span>{{ $invoice->invoice_number }} · {{ $invoice->status }}</span>
                <span>${{ $invoice->total }} due {{ $invoice->due_date }}</span>
            </div>
        @endforeach
        {{ $invoices->links() }}
    </div>
</div>
@endsection
