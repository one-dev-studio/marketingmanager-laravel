@extends('layouts.app')
@section('page-title', 'Competitions')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Competitions</h1>
        <a href="{{ route('main.competitions.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
    </div>
    <div class="bg-white border rounded-lg">
        @forelse($contests as $contest)
            <a class="block p-4 border-b" href="{{ route('main.competitions.show', ['organizationId' => $organizationId, 'contest' => $contest]) }}">
                {{ $contest->name }} · {{ $contest->status }} · {{ $contest->entries_count }} entries · {{ $contest->campaign?->name }}
            </a>
        @empty
            <div class="p-8 text-center text-gray-500">No competitions.</div>
        @endforelse
    </div>
    {{ $contests->links() }}
</div>
@endsection
