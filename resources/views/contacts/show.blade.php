@extends('layouts.app')
@section('page-title', $contact->full_name)
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">{{ $contact->full_name }}</h1>
            <p class="text-sm text-gray-500">{{ $contact->email }} · {{ $contact->status }}</p>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('main.email-marketing.contacts.subscribe', ['organizationId' => $organizationId, 'contact' => $contact]) }}">@csrf<button class="text-sm text-blue-700">Subscribe</button></form>
            <form method="POST" action="{{ route('main.email-marketing.contacts.unsubscribe', ['organizationId' => $organizationId, 'contact' => $contact]) }}">@csrf<button class="text-sm">Unsubscribe</button></form>
            <a class="text-sm" href="{{ route('main.email-marketing.contacts.export', ['organizationId' => $organizationId, 'contact' => $contact]) }}">GDPR export</a>
            <form method="POST" action="{{ route('main.email-marketing.contacts.delete-data', ['organizationId' => $organizationId, 'contact' => $contact]) }}" onsubmit="return confirm('Delete all personal data?');">@csrf @method('DELETE')<button class="text-sm text-red-600">GDPR delete</button></form>
        </div>
    </div>
    @if($duplicates->count())
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-sm">Possible duplicates: {{ $duplicates->pluck('email')->join(', ') }}</div>
    @endif
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-medium mb-2">Activity</h2>
        @forelse($contact->activities as $activity)
            <p class="text-sm text-gray-600">{{ $activity->created_at }} · {{ $activity->type ?? $activity->action ?? 'event' }}</p>
        @empty
            <p class="text-sm text-gray-500">No activity yet.</p>
        @endforelse
    </div>
</div>
@endsection
