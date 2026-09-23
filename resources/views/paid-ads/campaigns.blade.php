@extends('layouts.app')
@section('page-title', 'Paid Ads')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Paid Ad Campaigns</h1>
            <p class="text-sm text-gray-600">Budget, schedule, and performance</p>
        </div>
    </div>
    <form method="POST" action="{{ route('main.paid-campaigns.store', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4 grid md:grid-cols-4 gap-3">
        @csrf
        <input name="name" required class="rounded-md border-gray-300" placeholder="Campaign name">
        <select name="platform" class="rounded-md border-gray-300">
            @foreach(['facebook','instagram','google','linkedin','twitter','tiktok','pinterest'] as $p)
                <option value="{{ $p }}">{{ ucfirst($p) }}</option>
            @endforeach
        </select>
        <input name="budget" type="number" step="0.01" required class="rounded-md border-gray-300" placeholder="Budget">
        <select name="budget_type" class="rounded-md border-gray-300">
            <option value="daily">Daily</option>
            <option value="lifetime">Lifetime</option>
        </select>
        <input name="start_date" type="date" required class="rounded-md border-gray-300">
        <input name="end_date" type="date" class="rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
    </form>
    <div class="bg-white border rounded-lg divide-y">
        @forelse($paidCampaigns as $campaign)
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium">{{ $campaign->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $campaign->platform }} · {{ $campaign->status }} · {{ $campaign->currency ?? 'USD' }} {{ $campaign->budget }} ({{ $campaign->budget_type }})</p>
                </div>
                <p class="text-sm">Spent {{ $campaign->spent ?? 0 }}</p>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No paid campaigns yet.</div>
        @endforelse
    </div>
    {{ $paidCampaigns->links() }}
</div>
@endsection
