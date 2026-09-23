@extends('layouts.admin')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">Platform billing</h1>
    <div class="grid md:grid-cols-3 gap-4">
        <div class="border rounded-lg p-4"><p class="text-sm text-gray-500">Subscriptions</p><p class="text-2xl font-semibold">{{ $subscriptions->count() }}</p></div>
        <div class="border rounded-lg p-4"><p class="text-sm text-gray-500">Open invoices</p><p class="text-2xl font-semibold">{{ $openInvoices }}</p></div>
        <div class="border rounded-lg p-4"><p class="text-sm text-gray-500">Paid (30d)</p><p class="text-2xl font-semibold">{{ $paidTotal }}</p></div>
    </div>
    <table class="min-w-full bg-white border text-sm">
        <thead class="bg-gray-50"><tr><th class="p-3 text-left">Org</th><th class="p-3 text-left">Plan</th><th class="p-3 text-left">Status</th></tr></thead>
        <tbody>
        @foreach($subscriptions as $sub)
            <tr class="border-t"><td class="p-3">{{ $sub->organization?->name }}</td><td class="p-3">{{ $sub->plan?->name }}</td><td class="p-3">{{ $sub->status }}</td></tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
