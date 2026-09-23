@extends('layouts.app')
@section('page-title', $contest->name)
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">{{ $contest->name }}</h1>
            <p class="text-sm text-gray-500">{{ $contest->status }} · prize {{ $contest->prize }}</p>
        </div>
        <form method="POST" action="{{ route('main.competitions.close', ['organizationId' => $organizationId, 'contest' => $contest]) }}">@csrf<button class="border px-3 py-2 rounded-md">Close & pick winner</button></form>
    </div>
    <form method="POST" action="{{ route('main.competitions.entries.store', ['organizationId' => $organizationId, 'contest' => $contest]) }}" class="bg-white border rounded-lg p-4 grid md:grid-cols-4 gap-2">
        @csrf
        <input name="entrant_name" required placeholder="Name" class="rounded-md border-gray-300">
        <input name="entrant_email" type="email" required placeholder="Email" class="rounded-md border-gray-300">
        <input name="answer" placeholder="Entry" class="rounded-md border-gray-300">
        <button class="bg-blue-600 text-white rounded-md">Add entry</button>
    </form>
    <div class="bg-white border rounded-lg">
        @foreach($contest->entries as $entry)
            <div class="p-3 border-b text-sm flex justify-between">
                <span>{{ $entry->entrant_name }} · {{ $entry->entrant_email }} · {{ $entry->answer }}</span>
                @if($entry->is_winner)<span class="text-green-700">Winner</span>@endif
            </div>
        @endforeach
    </div>
</div>
@endsection
