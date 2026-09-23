@extends('layouts.app')

@section('page-title', $channel ? 'Edit Channel' : 'Add Channel')

@section('content')
@php $settings = $channel?->settings?->settings_json ?? []; @endphp
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-semibold text-gray-900">{{ $channel ? 'Edit Channel' : 'Add Channel' }}</h1>
    <form method="POST"
          action="{{ $channel ? route('main.social.channels.update', ['organizationId' => $organizationId, 'channel' => $channel]) : route('main.social.channels.store', ['organizationId' => $organizationId]) }}"
          class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf
        @if($channel) @method('PUT') @endif
        @include('channels._fields')
        <div class="flex gap-3">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
            <a href="{{ route('main.social.channels.index', ['organizationId' => $organizationId]) }}" class="px-4 py-2 border rounded-md">Cancel</a>
        </div>
    </form>
</div>
@endsection
