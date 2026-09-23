@extends('layouts.admin')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold">AI costing</h1>
    <form method="GET" class="flex gap-2">
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="rounded-md border-gray-300">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="rounded-md border-gray-300">
        <button class="border px-3 rounded-md">Filter</button>
    </form>
    <pre class="bg-white border rounded p-4 text-sm overflow-auto">{{ json_encode($costing, JSON_PRETTY_PRINT) }}</pre>
    <h2 class="font-semibold">Platform analytics</h2>
    <pre class="bg-white border rounded p-4 text-sm overflow-auto">{{ json_encode($platformAnalytics, JSON_PRETTY_PRINT) }}</pre>
    <h2 class="font-semibold">System health</h2>
    <pre class="bg-white border rounded p-4 text-sm overflow-auto">{{ json_encode($systemHealth, JSON_PRETTY_PRINT) }}</pre>
</div>
@endsection
