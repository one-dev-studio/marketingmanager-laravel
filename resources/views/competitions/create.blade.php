@extends('layouts.app')
@section('page-title', 'Create Competition')
@section('content')
<form method="POST" action="{{ route('main.competitions.store', ['organizationId' => $organizationId]) }}" class="max-w-xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <input name="name" required class="w-full rounded-md border-gray-300" placeholder="Name">
    <textarea name="description" class="w-full rounded-md border-gray-300" placeholder="Rules / description"></textarea>
    <input name="prize" class="w-full rounded-md border-gray-300" placeholder="Prize">
    <select name="campaign_id" class="w-full rounded-md border-gray-300">
        <option value="">Tied campaign (optional)</option>
        @foreach($campaigns as $campaign)
            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
        @endforeach
    </select>
    <div class="grid grid-cols-2 gap-2">
        <input type="datetime-local" name="starts_at" class="rounded-md border-gray-300">
        <input type="datetime-local" name="ends_at" class="rounded-md border-gray-300">
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
</form>
@endsection
