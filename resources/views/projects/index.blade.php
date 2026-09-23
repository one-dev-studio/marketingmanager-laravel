@extends('layouts.app')
@section('page-title', 'Projects')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Projects</h1>
        <form method="POST" action="{{ route('main.projects.store', ['organizationId' => $organizationId]) }}" class="flex gap-2">
            @csrf
            <input name="name" required placeholder="New project" class="rounded-md border-gray-300">
            <select name="status" class="rounded-md border-gray-300">
                @foreach(['planning','in_progress','review','completed'] as $status)
                    <option value="{{ $status }}">{{ str_replace('_',' ', $status) }}</option>
                @endforeach
            </select>
            <button class="bg-blue-600 text-white px-3 rounded-md">Create</button>
        </form>
    </div>
    @if($templates->count())
        <div class="text-sm">Templates:
            @foreach($templates as $template)
                <form class="inline" method="POST" action="{{ route('main.projects.templates.create-project', ['organizationId' => $organizationId, 'projectTemplate' => $template]) }}">@csrf<button class="text-blue-700">{{ $template->name }}</button></form>
            @endforeach
        </div>
    @endif
    <div class="grid md:grid-cols-2 gap-4">
        @forelse($projects as $project)
            <div class="bg-white border rounded-lg p-4">
                <h2 class="font-medium">{{ $project->name }}</h2>
                <p class="text-sm text-gray-500 capitalize">{{ str_replace('_',' ', $project->status) }} · {{ $project->progress }}%</p>
                <div class="h-2 bg-gray-100 rounded mt-2"><div class="h-2 bg-blue-600 rounded" style="width: {{ $project->progress }}%"></div></div>
                <p class="text-xs text-gray-400 mt-2">{{ $project->members->pluck('name')->join(', ') }} · {{ $project->client?->name }}</p>
            </div>
        @empty
            <div class="col-span-2 p-8 text-center text-gray-500 bg-white border rounded-lg">No projects.</div>
        @endforelse
    </div>
    {{ $projects->links() }}
</div>
@endsection
