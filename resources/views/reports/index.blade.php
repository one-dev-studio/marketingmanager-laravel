@extends('layouts.app')
@section('page-title', 'Reports')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-semibold">Reports</h1>
        <a href="{{ route('main.reports.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Builder</a>
    </div>
    <div class="bg-white border rounded-lg">
        @forelse($reports as $report)
            <div class="p-4 border-b flex justify-between">
                <a href="{{ route('main.reports.show', ['organizationId' => $organizationId, 'reportId' => $report->id]) }}">{{ $report->name }}</a>
                <form method="POST" action="{{ route('main.reports.generate', ['organizationId' => $organizationId, 'reportId' => $report->id]) }}">@csrf<button class="text-sm text-blue-700">Generate</button></form>
                <form method="POST" action="{{ route('main.reports.export', ['organizationId' => $organizationId, 'reportId' => $report->id]) }}">@csrf<input type="hidden" name="format" value="pdf"><button class="text-sm">PDF</button></form>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No reports.</div>
        @endforelse
    </div>
    {{ $reports->links() }}
</div>
@endsection
