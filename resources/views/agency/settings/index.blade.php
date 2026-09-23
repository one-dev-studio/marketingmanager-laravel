@extends('layouts.agency')
@section('content')
<div class="space-y-6" x-data="{ tab: 'profile' }">
    <h1 class="text-2xl font-semibold">Agency settings</h1>
    <div class="flex gap-2">
        @foreach(['profile','branding','defaults','integrations','notifications'] as $tab)
            <button class="px-3 py-1 border rounded" @click="tab='{{ $tab }}'">{{ ucfirst($tab) }}</button>
        @endforeach
    </div>
    <form method="POST" action="{{ route('agency.settings.update-profile', $agency) }}" x-show="tab==='profile'" class="bg-white border rounded-lg p-4 space-y-3">
        @csrf @method('PUT')
        <input name="name" value="{{ $agency->name }}" class="w-full rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save profile</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-branding', $agency) }}" x-show="tab==='branding'" x-cloak class="bg-white border rounded-lg p-4 space-y-3">
        @csrf @method('PUT')
        <input name="logo" value="{{ $agency->logo }}" class="w-full rounded-md border-gray-300" placeholder="Logo URL">
        <input name="primary_color" value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="w-full rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save branding</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-defaults', $agency) }}" x-show="tab==='defaults'" x-cloak class="bg-white border rounded-lg p-4 space-y-3">
        @csrf @method('PUT')
        <input name="timezone" value="{{ $settings['timezone'] ?? 'UTC' }}" class="w-full rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save defaults</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-integrations', $agency) }}" x-show="tab==='integrations'" x-cloak class="bg-white border rounded-lg p-4 space-y-3">
        @csrf @method('PUT')
        <input name="slack_webhook" value="{{ $settings['slack_webhook'] ?? '' }}" class="w-full rounded-md border-gray-300" placeholder="Slack webhook">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save integrations</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-notifications', $agency) }}" x-show="tab==='notifications'" x-cloak class="bg-white border rounded-lg p-4 space-y-3">
        @csrf @method('PUT')
        <label><input type="checkbox" name="notify_email" value="1"> Email</label>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save notifications</button>
    </form>
</div>
@endsection
