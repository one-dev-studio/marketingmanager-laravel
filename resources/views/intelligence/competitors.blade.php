@extends('layouts.app')
@section('page-title', 'Competitors')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Competitor analysis</h1>
    <form method="POST" action="{{ route('main.competitors.store', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4 grid md:grid-cols-3 gap-3">
        @csrf
        <input name="name" required class="rounded-md border-gray-300" placeholder="Competitor name">
        <input name="website" class="rounded-md border-gray-300" placeholder="https://">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Add competitor</button>
    </form>
    <div class="bg-white border rounded-lg divide-y">
        @forelse($competitors as $competitor)
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium">{{ $competitor->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $competitor->website }} · {{ $competitor->analyses->count() }} analyses</p>
                </div>
                <form method="POST" action="{{ route('main.competitors.analysis', ['organizationId' => $organizationId, 'competitor' => $competitor]) }}">
                    @csrf
                    <button class="text-sm text-blue-700">Run analysis</button>
                </form>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No competitors tracked.</div>
        @endforelse
    </div>
    {{ $competitors->links() }}
</div>
@endsection
