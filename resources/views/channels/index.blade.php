@extends('layouts.app')

@section('page-title', 'Channels')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Channels</h1>
            <p class="mt-1 text-sm text-gray-600">Connect social, email, ads, and influencer destinations</p>
        </div>
        @can('create', App\Models\Channel::class)
            <a href="{{ route('main.social.channels.create', ['organizationId' => $organizationId]) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Add Channel</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-md bg-red-50 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h2 class="text-sm font-medium text-gray-900 mb-3">OAuth connect</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($oauthPlatforms as $platform)
                <a href="{{ route('main.social.auth.redirect', ['organizationId' => $organizationId, 'platform' => $platform]) }}"
                   class="border border-gray-300 px-3 py-1.5 rounded-md text-sm capitalize hover:bg-gray-50">
                    Connect {{ $platform === 'twitter' ? 'X' : $platform }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($channels as $channel)
            @php
                $connected = $channel->socialConnection?->isConnected();
            @endphp
            <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $channel->display_name }}</h3>
                        <p class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $channel->type) }}
                            @if($channel->platform) · {{ $channel->platform }} @endif
                        </p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $connected ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                        {{ $connected ? 'Connected' : ($channel->status ?? 'disconnected') }}
                    </span>
                </div>
                @if($channel->type === 'influencer')
                    <p class="text-sm text-gray-600">
                        Followers: {{ number_format($channel->settings?->settings_json['follower_count'] ?? 0) }}
                        · Engagement: {{ $channel->settings?->settings_json['engagement_rate'] ?? 0 }}%
                    </p>
                @endif
                <div class="flex items-center gap-3 text-sm">
                    <form method="POST" action="{{ route('main.social.channels.test', ['organizationId' => $organizationId, 'channel' => $channel]) }}">
                        @csrf
                        <button class="text-blue-600 hover:text-blue-800">Test</button>
                    </form>
                    <a href="{{ route('main.social.channels.edit', ['organizationId' => $organizationId, 'channel' => $channel]) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                    @if($channel->socialConnection)
                        <form method="POST" action="{{ route('main.social.connections.destroy', ['organizationId' => $organizationId, 'connection' => $channel->socialConnection]) }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-gray-600 hover:text-gray-800">Disconnect</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('main.social.channels.destroy', ['organizationId' => $organizationId, 'channel' => $channel]) }}"
                          onsubmit="return confirm('Delete this channel?');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg border border-gray-200 p-8 text-center text-gray-500">No channels yet.</div>
        @endforelse
    </div>
</div>
@endsection
