@php
    use App\Support\Agency\AgencyPortalBrandingKeys;
@endphp
@extends('layouts.agency')
@section('content')
<div class="space-y-6" x-data="{ tab: 'profile' }">
    <h1 class="text-2xl font-semibold">Agency settings</h1>
    <div class="flex flex-wrap gap-2">
        @foreach(['profile','branding','defaults','integrations','notifications'] as $tabName)
            <button type="button" class="rounded border px-3 py-1" @click="tab='{{ $tabName }}'">{{ ucfirst($tabName) }}</button>
        @endforeach
    </div>
    <form method="POST" action="{{ route('agency.settings.update-profile', $agency) }}" x-show="tab==='profile'" class="space-y-3 rounded-lg border bg-white p-4">
        @csrf @method('PUT')
        <label class="block text-sm font-medium text-gray-700">Agency name</label>
        <input name="name" value="{{ $agency->name }}" class="w-full rounded-md border-gray-300">
        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Save profile</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-branding', $agency) }}" x-show="tab==='branding'" x-cloak class="space-y-4 rounded-lg border bg-white p-4">
        @csrf @method('PUT')
        <div>
            <h2 class="text-lg font-medium text-gray-900">Agency brand</h2>
            <p class="mt-1 text-sm text-gray-600">Used on agency-facing screens and exports.</p>
        </div>
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">Logo URL</label>
            <input name="logo" value="{{ $branding['logo'] ?? $agency->logo }}" class="w-full rounded-md border-gray-300" placeholder="https://…">
            <label class="block text-sm font-medium text-gray-700">Primary color</label>
            <input name="primary_color" value="{{ $branding['primary_color'] ?? '#4f46e5' }}" class="w-full rounded-md border-gray-300" placeholder="#4f46e5">
            <label class="block text-sm font-medium text-gray-700">Secondary color</label>
            <input name="secondary_color" value="{{ $branding['secondary_color'] ?? '#6366f1' }}" class="w-full rounded-md border-gray-300" placeholder="#6366f1">
        </div>
        <div class="space-y-3 border-t border-gray-200 pt-4">
            <h2 class="text-lg font-medium text-gray-900">Client portal white-label</h2>
            <p class="text-sm text-gray-600">Branding shown on the client portal layout (Track 4).</p>
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input
                    type="checkbox"
                    name="{{ AgencyPortalBrandingKeys::ENABLED }}"
                    value="1"
                    @checked($branding[AgencyPortalBrandingKeys::ENABLED] ?? false)
                >
                Enable client portal for this agency
            </label>
            <label class="block text-sm font-medium text-gray-700">Portal title</label>
            <input
                name="{{ AgencyPortalBrandingKeys::TITLE }}"
                value="{{ $branding[AgencyPortalBrandingKeys::TITLE] ?? '' }}"
                class="w-full rounded-md border-gray-300"
                placeholder="Client Hub"
            >
            <label class="block text-sm font-medium text-gray-700">Welcome text</label>
            <textarea
                name="{{ AgencyPortalBrandingKeys::WELCOME_TEXT }}"
                rows="3"
                class="w-full rounded-md border-gray-300"
                placeholder="Welcome to your campaign portal."
            >{{ $branding[AgencyPortalBrandingKeys::WELCOME_TEXT] ?? '' }}</textarea>
            <label class="block text-sm font-medium text-gray-700">Portal logo URL</label>
            <input
                name="{{ AgencyPortalBrandingKeys::LOGO_URL }}"
                value="{{ $branding[AgencyPortalBrandingKeys::LOGO_URL] ?? '' }}"
                class="w-full rounded-md border-gray-300"
                placeholder="https://…"
            >
            <label class="block text-sm font-medium text-gray-700">Portal primary color</label>
            <input
                name="{{ AgencyPortalBrandingKeys::PRIMARY_COLOR }}"
                value="{{ $branding[AgencyPortalBrandingKeys::PRIMARY_COLOR] ?? '#4f46e5' }}"
                class="w-full rounded-md border-gray-300"
                placeholder="#4f46e5"
            >
            <label class="block text-sm font-medium text-gray-700">Portal secondary color</label>
            <input
                name="{{ AgencyPortalBrandingKeys::SECONDARY_COLOR }}"
                value="{{ $branding[AgencyPortalBrandingKeys::SECONDARY_COLOR] ?? '#6366f1' }}"
                class="w-full rounded-md border-gray-300"
                placeholder="#6366f1"
            >
        </div>
        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Save branding</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-defaults', $agency) }}" x-show="tab==='defaults'" x-cloak class="space-y-3 rounded-lg border bg-white p-4">
        @csrf @method('PUT')
        <input name="timezone" value="{{ $settings['defaults']['timezone'] ?? 'UTC' }}" class="w-full rounded-md border-gray-300">
        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Save defaults</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-integrations', $agency) }}" x-show="tab==='integrations'" x-cloak class="space-y-3 rounded-lg border bg-white p-4">
        @csrf @method('PUT')
        <input name="slack_webhook" value="{{ $settings['integrations']['slack_webhook'] ?? '' }}" class="w-full rounded-md border-gray-300" placeholder="Slack webhook">
        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Save integrations</button>
    </form>
    <form method="POST" action="{{ route('agency.settings.update-notifications', $agency) }}" x-show="tab==='notifications'" x-cloak class="space-y-3 rounded-lg border bg-white p-4">
        @csrf @method('PUT')
        <label><input type="checkbox" name="notify_email" value="1"> Email</label>
        <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Save notifications</button>
    </form>
</div>
@endsection
