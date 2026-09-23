@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Platform Settings</h1>
        <p class="mt-1 text-gray-600">Configure global platform settings and feature flags</p>
    </div>

    <div class="rounded-lg border border-gray-200 p-4 space-y-2">
        <p class="text-sm text-gray-500">Maintenance Mode</p>
        <p class="text-lg font-semibold text-gray-900">{{ $maintenanceMode ? 'Enabled' : 'Disabled' }}</p>
        @if($maintenanceMode)
            <form method="POST" action="{{ route('admin.settings.disable-maintenance') }}">@csrf<button class="text-sm text-blue-700">Disable</button></form>
        @else
            <form method="POST" action="{{ route('admin.settings.enable-maintenance') }}">@csrf<button class="text-sm text-blue-700">Enable</button></form>
        @endif
    </div>
    <form method="POST" action="{{ route('admin.settings.update') }}" class="rounded-lg border p-4 space-y-2">
        @csrf
        <h2 class="font-semibold">Update setting</h2>
        <input name="key" class="rounded-md border-gray-300" placeholder="key" required>
        <input name="value" class="rounded-md border-gray-300" placeholder="value" required>
        <button class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
    </form>
    <form method="POST" action="{{ route('admin.settings.update-api-key') }}" class="rounded-lg border p-4 space-y-2">
        @csrf
        <h2 class="font-semibold">API key</h2>
        <input name="name" class="rounded-md border-gray-300" placeholder="name">
        <input name="key" class="rounded-md border-gray-300" placeholder="value">
        <button class="bg-blue-600 text-white px-3 py-1 rounded">Save key</button>
    </form>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Global Settings</h2>
        @if(empty($settings))
            <p class="mt-2 text-sm text-gray-500">No global settings configured.</p>
        @else
            <dl class="mt-4 space-y-2">
                @foreach($settings as $key => $value)
                    <div class="flex justify-between text-sm">
                        <dt class="text-gray-600">{{ $key }}</dt>
                        <dd class="text-gray-900">{{ is_array($value) ? json_encode($value) : $value }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Feature Flags</h2>
        @if($featureFlags->isEmpty())
            <p class="mt-2 text-sm text-gray-500">No feature flags configured.</p>
        @else
            <ul class="mt-4 space-y-2">
                @foreach($featureFlags as $flag)
                    <li class="text-sm text-gray-600">{{ $flag->name }} · {{ $flag->enabled ? 'Enabled' : 'Disabled' }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
