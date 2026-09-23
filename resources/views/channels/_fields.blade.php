@php $settings = $channel?->settings?->settings_json ?? []; @endphp
<div>
    <label class="block text-sm font-medium text-gray-700">Name</label>
    <input name="display_name" value="{{ old('display_name', $channel?->display_name) }}" required class="mt-1 w-full rounded-md border-gray-300">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Type</label>
    <select name="type" {{ $channel ? 'disabled' : '' }} class="mt-1 w-full rounded-md border-gray-300">
        @foreach(['social','email','whatsapp','amplify','paid_ads','press_release','influencer'] as $type)
            <option value="{{ $type }}" @selected(old('type', $channel?->type) === $type)>{{ str_replace('_',' ', $type) }}</option>
        @endforeach
    </select>
    @if($channel)<input type="hidden" name="type" value="{{ $channel->type }}">@endif
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Platform</label>
    <input name="platform" value="{{ old('platform', $channel?->platform) }}" class="mt-1 w-full rounded-md border-gray-300">
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Status</label>
    <select name="status" class="mt-1 w-full rounded-md border-gray-300">
        @foreach(['active','inactive','disconnected'] as $status)
            <option value="{{ $status }}" @selected(old('status', $channel?->status ?? 'active') === $status)>{{ $status }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Credentials / API key (stored in settings)</label>
    <input name="settings[api_key]" value="{{ old('settings.api_key', $settings['api_key'] ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" autocomplete="off">
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Influencer followers</label>
        <input type="number" name="follower_count" min="0" value="{{ old('follower_count', $settings['follower_count'] ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Engagement %</label>
        <input type="number" step="0.01" name="engagement_rate" value="{{ old('engagement_rate', $settings['engagement_rate'] ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
    </div>
</div>
<div>
    <label class="block text-sm font-medium text-gray-700">Handle</label>
    <input name="handle" value="{{ old('handle', $settings['handle'] ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
</div>
