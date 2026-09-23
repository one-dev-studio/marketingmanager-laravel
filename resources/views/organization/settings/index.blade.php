@extends('layouts.app')
@section('page-title', 'Settings')
@section('content')
<div x-data="{ tab: 'general' }" class="space-y-4">
    <h1 class="text-2xl font-semibold">Organization Settings</h1>
    <div class="flex gap-2">
        @foreach(['general','integrations','notifications','security'] as $tab)
            <button class="px-3 py-1 rounded border" :class="tab==='{{ $tab }}' ? 'bg-blue-600 text-white' : ''" @click="tab='{{ $tab }}'">{{ ucfirst($tab) }}</button>
        @endforeach
    </div>
    <form method="POST" action="{{ route('main.settings.update', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-6 space-y-3">
        @csrf @method('PUT')
        <div x-show="tab==='general'">
            <input name="name" value="{{ $organization->name }}" class="w-full rounded-md border-gray-300">
            <input name="timezone" value="{{ $organization->timezone }}" class="w-full rounded-md border-gray-300 mt-2" placeholder="Timezone">
            <input name="locale" value="{{ $organization->locale }}" class="w-full rounded-md border-gray-300 mt-2">
        </div>
        <div x-show="tab==='integrations'" x-cloak>
            <input name="settings[slack_webhook]" value="{{ $settings['slack_webhook'] ?? '' }}" class="w-full rounded-md border-gray-300" placeholder="Slack webhook">
        </div>
        <div x-show="tab==='notifications'" x-cloak>
            <label class="block text-sm"><input type="checkbox" name="settings[notify_email]" value="1" @checked(!empty($settings['notify_email']))> Email notifications</label>
            <label class="block text-sm"><input type="checkbox" name="settings[notify_in_app]" value="1" @checked(!empty($settings['notify_in_app']))> In-app</label>
            <label class="block text-sm"><input type="checkbox" name="settings[notify_push]" value="1" @checked(!empty($settings['notify_push']))> Push</label>
        </div>
        <div x-show="tab==='security'" x-cloak>
            <label class="block text-sm"><input type="checkbox" name="settings[require_2fa]" value="1" @checked(!empty($settings['require_2fa']))> Require 2FA</label>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
    </form>
</div>
@endsection
