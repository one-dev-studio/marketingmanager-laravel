@extends('layouts.agency')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">{{ $client->name }}</h1>
    <p class="text-sm text-gray-600">{{ $client->users_count ?? $client->users()->count() }} users · {{ $client->status ?? 'active' }}</p>
    <a class="text-blue-700" href="{{ route('main.dashboard', ['organizationId' => $client->id]) }}">Open customer workspace</a>
</div>
@endsection
