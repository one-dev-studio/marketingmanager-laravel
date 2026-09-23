@extends('layouts.app')
@section('page-title', 'Landing Pages')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Landing Pages</h1>
            <p class="mt-1 text-sm text-gray-600">Build, preview, and publish</p>
        </div>
        <a href="{{ route('main.landing-pages.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
    </div>
    <div class="bg-white rounded-lg border overflow-hidden">
        @forelse($landingPages as $page)
            <div class="p-4 border-b flex items-center justify-between">
                <div>
                    <h2 class="font-medium">{{ $page->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $page->status }} · /{{ $page->slug }}</p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a class="text-blue-700" href="{{ route('main.landing-pages.builder', ['organizationId' => $organizationId, 'landingPage' => $page]) }}">Builder</a>
                    <a href="{{ route('main.landing-pages.preview', ['organizationId' => $organizationId, 'landingPage' => $page]) }}">Preview</a>
                    <a href="{{ route('main.landing-pages.analytics', ['organizationId' => $organizationId, 'landingPage' => $page]) }}">Analytics</a>
                    <form method="POST" action="{{ route('main.landing-pages.publish', ['organizationId' => $organizationId, 'landingPage' => $page]) }}">@csrf<button class="text-blue-700">Publish</button></form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No landing pages yet.</div>
        @endforelse
    </div>
    {{ $landingPages->links() }}
</div>
@endsection
