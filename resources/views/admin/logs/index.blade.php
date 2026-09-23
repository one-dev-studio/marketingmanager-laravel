@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">System Logs</h1>
        <p class="mt-1 text-gray-600">View platform system logs and performance metrics</p>
    </div>

    @if(!empty($performanceMetrics))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($performanceMetrics as $metric => $value)
                <div class="rounded-lg border border-gray-200 p-4">
                    <p class="text-sm text-gray-500">{{ ucwords(str_replace('_', ' ', $metric)) }}</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ is_array($value) ? json_encode($value) : $value }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $log->level ?? 'info' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ \Illuminate\Support\Str::limit($log->message ?? '', 120) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $log->created_at?->format('M j, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No system logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($logs, 'links'))
        {{ $logs->links() }}
    @endif
</div>
@endsection
