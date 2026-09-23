<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Invoice {{ $invoice->invoice_number }}</title>
<style>body{font-family:sans-serif;font-size:12px} table{width:100%;border-collapse:collapse} td,th{border:1px solid #ccc;padding:6px}</style>
</head>
<body>
<h1>Invoice {{ $invoice->invoice_number }}</h1>
<p>{{ $invoice->organization?->name }}</p>
<p>Status: {{ $invoice->status }} · Due {{ $invoice->due_date }}</p>
<table>
    <tr><th>Description</th><th>Amount</th></tr>
    @foreach($invoice->invoiceItems ?? [] as $item)
        <tr><td>{{ $item->description }}</td><td>{{ $item->amount }}</td></tr>
    @endforeach
    <tr><td>Subtotal</td><td>{{ $invoice->subtotal }}</td></tr>
    <tr><td>Tax</td><td>{{ $invoice->tax }}</td></tr>
    <tr><td>Total</td><td>{{ $invoice->total }} {{ $invoice->currency }}</td></tr>
</table>
</body>
</html>
