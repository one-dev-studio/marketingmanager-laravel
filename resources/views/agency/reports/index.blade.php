@extends('layouts.agency')
@section('content')
<div class="space-y-6" x-data="{ loading: false }">
    <h1 class="text-2xl font-semibold">Client reports</h1>
    <form method="POST" action="{{ route('agency.reports.generate', $agency) }}" class="bg-white border rounded-lg p-4 flex gap-3" @submit="loading = true">
        @csrf
        <select name="organization_id" required class="rounded-md border-gray-300">
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>
        <select name="report_type" class="rounded-md border-gray-300">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
        </select>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="loading" x-text="loading ? 'Generating…' : 'Generate'"></button>
    </form>
    <div class="bg-white border rounded-lg divide-y">
        @forelse($reports as $report)
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium">{{ $report->name ?? $report->type }}</h2>
                    <p class="text-sm text-gray-500">{{ $report->status }} · {{ $report->created_at }}</p>
                    @if(is_array($report->data ?? null))
                        <p class="text-sm mt-1">{{ $report->data['executive_summary'] ?? '' }}</p>
                    @endif
                </div>
                <a class="text-blue-700 text-sm" href="{{ route('agency.reports.download', [$agency, $report]) }}">Download PDF</a>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No reports yet.</div>
        @endforelse
    </div>
    {{ $reports->links() }}
</div>
@endsection
