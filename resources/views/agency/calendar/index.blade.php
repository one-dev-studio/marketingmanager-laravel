@extends('layouts.agency')
@section('content')
<div class="space-y-4" x-data="{ filter: '' }">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Aggregated calendar</h1>
        <select x-model="filter" class="rounded-md border-gray-300">
            <option value="">All clients</option>
            @foreach($agency->clientOrganizations as $org)
                <option value="{{ $org->id }}">{{ $org->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
        @foreach($scheduledPosts as $post)
            <div class="bg-white border rounded-lg p-4" x-show="!filter || filter == '{{ $post->organization_id }}'"
                 style="border-left: 4px solid hsl({{ ($post->organization_id * 40) % 360 }} 70% 45%)">
                <p class="text-xs text-gray-500">{{ $post->organization?->name }}</p>
                <h2 class="font-medium">{{ $post->title ?? $post->campaign?->name ?? 'Scheduled post' }}</h2>
                <p class="text-sm">{{ $post->scheduled_at }}</p>
            </div>
        @endforeach
        @foreach($campaigns as $campaign)
            <div class="bg-white border rounded-lg p-4" x-show="!filter || filter == '{{ $campaign->organization_id }}'"
                 style="border-left: 4px solid hsl({{ ($campaign->organization_id * 40) % 360 }} 70% 45%)">
                <p class="text-xs text-gray-500">{{ $campaign->organization?->name }} · Launch</p>
                <h2 class="font-medium">{{ $campaign->name }}</h2>
                <p class="text-sm">{{ $campaign->start_date }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
