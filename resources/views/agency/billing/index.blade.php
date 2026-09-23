@extends('layouts.agency')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Billing & invoicing</h1>
    <div class="grid md:grid-cols-3 gap-4">
        <div class="bg-white border rounded-lg p-4"><p class="text-sm text-gray-500">Total billed (YTD)</p><p class="text-2xl font-semibold">{{ $summary['ytd'] ?? $summary['total'] ?? 0 }}</p></div>
        <div class="bg-white border rounded-lg p-4"><p class="text-sm text-gray-500">Pending</p><p class="text-2xl font-semibold">{{ $summary['pending'] ?? 0 }}</p></div>
        <div class="bg-white border rounded-lg p-4"><p class="text-sm text-gray-500">Overdue</p><p class="text-2xl font-semibold">{{ $summary['overdue'] ?? 0 }}</p></div>
    </div>
    <form method="POST" action="{{ route('agency.billing.send-reminders', $agency) }}">@csrf<button class="text-sm text-blue-700">Run payment reminders</button></form>
    <table class="min-w-full bg-white border rounded-lg text-sm">
        <thead class="bg-gray-50"><tr>
            <th class="p-3 text-left">ID</th><th class="p-3 text-left">Org</th><th class="p-3 text-left">Amount</th><th class="p-3 text-left">Status</th><th class="p-3 text-left">Due</th><th></th>
        </tr></thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr class="border-t">
                <td class="p-3">{{ $invoice->invoice_number }}</td>
                <td class="p-3">{{ $invoice->organization?->name }}</td>
                <td class="p-3">{{ $invoice->total }}</td>
                <td class="p-3"><span class="px-2 py-0.5 rounded text-xs {{ $invoice->status === 'paid' ? 'bg-green-100' : ($invoice->status === 'overdue' ? 'bg-red-100' : 'bg-yellow-100') }}">{{ $invoice->status }}</span></td>
                <td class="p-3">{{ $invoice->due_date }}</td>
                <td class="p-3 text-right space-x-2">
                    <a href="{{ route('agency.billing.download', [$agency, $invoice]) }}">PDF</a>
                    @if($invoice->status !== 'paid')
                        <form class="inline" method="POST" action="{{ route('agency.billing.pay', [$agency, $invoice]) }}">@csrf<button>Mark paid</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
