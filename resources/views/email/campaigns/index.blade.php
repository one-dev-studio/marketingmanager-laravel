@extends('layouts.app')
@section('page-title', 'Email Campaigns')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Email Campaigns</h1>
        <div class="flex gap-2">
            <a href="{{ route('main.email-marketing.templates.index', ['organizationId' => $organizationId]) }}" class="border px-4 py-2 rounded-md">Templates</a>
            <a href="{{ route('main.email-marketing.campaigns.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
        </div>
    </div>
    <div class="bg-white border rounded-lg overflow-hidden">
        @forelse($campaigns as $campaign)
            <div class="p-4 border-b flex justify-between">
                <div>
                    <a class="font-medium" href="{{ route('main.email-marketing.campaigns.show', ['organizationId' => $organizationId, 'emailCampaign' => $campaign]) }}">{{ $campaign->name }}</a>
                    <p class="text-sm text-gray-500">{{ $campaign->subject }} · {{ $campaign->status }} · {{ optional($campaign->scheduled_at)->toDayDateTimeString() ?? $campaign->sent_at }}</p>
                    <p class="text-xs text-gray-400">Opens {{ $campaign->opened_count }} · Clicks {{ $campaign->clicked_count }}</p>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No campaigns.</div>
        @endforelse
    </div>
    {{ $campaigns->links() }}
</div>
@endsection
