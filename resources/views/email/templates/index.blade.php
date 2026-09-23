@extends('layouts.app')
@section('page-title', 'Templates')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Email Templates</h1>
        <a href="{{ route('main.email-marketing.templates.builder', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Builder</a>
    </div>
    <div class="bg-white border rounded-lg">
        @forelse($templates as $template)
            <a class="block p-4 border-b" href="{{ route('main.email-marketing.templates.builder', ['organizationId' => $organizationId, 'emailTemplate' => $template]) }}">{{ $template->name }} · {{ $template->subject }}</a>
        @empty
            <div class="p-8 text-center text-gray-500">No templates.</div>
        @endforelse
    </div>
    {{ $templates->links() }}
</div>
@endsection
