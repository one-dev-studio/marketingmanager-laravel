@extends('layouts.app')
@section('page-title', 'Tasks')
@section('content')
<div id="task-kanban-app">
    <task-kanban organization-id="{{ $organizationId }}" :members='@json($members)'></task-kanban>
    @if($templates->count())
        <div class="mt-6 bg-white border rounded-lg p-4">
            <h2 class="font-medium mb-2">Templates</h2>
            @foreach($templates as $template)
                <form class="inline-block mr-2" method="POST" action="{{ route('main.tasks.templates.create-task', ['organizationId' => $organizationId, 'taskTemplate' => $template]) }}">
                    @csrf
                    <button class="text-sm text-blue-700">Use {{ $template->name }}</button>
                </form>
            @endforeach
        </div>
    @endif
</div>
@endsection
