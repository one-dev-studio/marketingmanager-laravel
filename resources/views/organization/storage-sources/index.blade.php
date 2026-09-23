@extends('layouts.app')
@section('page-title', 'Storage')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Storage sources</h1>
    <p class="text-sm text-gray-600">Quota {{ $quota['used_human'] }} / {{ $quota['limit_human'] }}</p>
    @foreach(['s3' => 'Amazon S3', 'google_drive' => 'Google Drive', 'dropbox' => 'Dropbox'] as $provider => $label)
        @php $source = collect($sources)->firstWhere('provider', $provider); @endphp
        <div class="bg-white border rounded-lg p-4 flex justify-between">
            <div>
                <h2 class="font-medium">{{ $label }}</h2>
                <p class="text-sm text-gray-500">{{ $source['is_connected'] ?? false ? 'Connected' : 'Not connected' }}</p>
            </div>
            @if($source['is_connected'] ?? false)
                <form method="POST" action="{{ route('main.storage-sources.disconnect', ['organizationId' => $organizationId, 'provider' => $provider]) }}">@csrf @method('DELETE')<button class="text-red-600 text-sm">Disconnect</button></form>
            @else
                <form method="POST" action="{{ route('main.storage-sources.connect', ['organizationId' => $organizationId]) }}" class="flex gap-2">
                    @csrf
                    <input type="hidden" name="provider" value="{{ $provider }}">
                    <input name="access_token" required placeholder="Access token / key" class="rounded-md border-gray-300 text-sm">
                    <button class="text-blue-700 text-sm">Connect</button>
                </form>
            @endif
        </div>
    @endforeach
</div>
@endsection
