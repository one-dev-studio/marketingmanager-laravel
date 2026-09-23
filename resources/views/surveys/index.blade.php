@extends('layouts.app')
@section('page-title', 'Surveys')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Surveys</h1>
            <p class="text-sm text-gray-600">Build, distribute, and analyze</p>
        </div>
        <a href="{{ route('main.surveys.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</a>
    </div>
    <div class="bg-white border rounded-lg">
        @forelse($surveys as $survey)
            <div class="p-4 border-b flex justify-between">
                <div>
                    <h2 class="font-medium">{{ $survey->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $survey->status }} · {{ $survey->response_count }} responses</p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a class="text-blue-700" href="{{ route('main.surveys.builder', ['organizationId' => $organizationId, 'survey' => $survey]) }}">Builder</a>
                    <a href="{{ route('main.surveys.analytics', ['organizationId' => $organizationId, 'survey' => $survey]) }}">Analytics</a>
                    <a href="{{ route('public.survey', $survey) }}">Public link</a>
                    <a href="{{ route('main.surveys.export', ['organizationId' => $organizationId, 'survey' => $survey]) }}">Export</a>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No surveys yet.</div>
        @endforelse
    </div>
    {{ $surveys->links() }}
</div>
@endsection
