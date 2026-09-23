@extends('layouts.app')

@section('page-title', 'Organizations')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Your organizations</h1>
        <p class="mt-1 text-sm text-gray-600">Select an organization to continue, or complete onboarding to create one.</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @forelse($organizations as $organization)
            <a href="{{ route('main.dashboard', ['organizationId' => $organization->id]) }}"
               class="block p-4 border-b border-gray-100 hover:bg-gray-50">
                <p class="font-medium text-gray-900">{{ $organization->name }}</p>
                <p class="text-sm text-gray-500">{{ $organization->status }}</p>
            </a>
        @empty
            <div class="p-8 text-center text-gray-500">
                <p>You don’t belong to an organization yet.</p>
                <a href="{{ route('main.onboarding') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Start onboarding</a>
            </div>
        @endforelse
    </div>

    @if($organizations->isNotEmpty())
        <a href="{{ route('main.onboarding') }}" class="text-sm text-blue-600 hover:text-blue-800">Run setup wizard</a>
    @endif
</div>
@endsection
