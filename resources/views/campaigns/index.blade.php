@extends('layouts.app')

@section('page-title', 'Campaigns')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Campaigns</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your marketing campaigns</p>
        </div>
        <a href="{{ route('main.campaigns.create', ['organizationId' => $organizationId]) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Create Campaign
        </a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @forelse($campaigns as $campaign)
            <div class="p-4 border-b border-gray-100 flex items-center justify-between hover:bg-gray-50">
                <div>
                    <h2 class="font-medium text-gray-900">{{ $campaign->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $campaign->status }} · {{ $campaign->brand?->name ?? 'No brand' }}</p>
                </div>
                <a href="{{ route('main.campaigns.show', ['organizationId' => $organizationId, 'campaign' => $campaign]) }}"
                   class="text-blue-600 hover:text-blue-800 text-sm">View</a>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No campaigns yet.</div>
        @endforelse
    </div>

    {{ $campaigns->links() }}
</div>
@endsection
