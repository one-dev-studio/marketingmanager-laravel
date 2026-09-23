@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Content Moderation</h1>
        <p class="mt-1 text-gray-600">Review flagged content and moderation queue</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Moderation Queue</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $moderationQueue->total() }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Pending Flags</p>
            <p class="text-2xl font-semibold text-gray-900">{{ $contentFlags->total() }}</p>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Pending Flags</h2>
        @if($contentFlags->isEmpty())
            <p class="mt-2 text-sm text-gray-500">No pending content flags.</p>
        @else
            <ul class="mt-4 divide-y divide-gray-100">
                @foreach($contentFlags as $flag)
                    <li class="py-3 text-sm text-gray-600">
                        Flag #{{ $flag->id }} · {{ $flag->reason ?? 'No reason' }} · {{ $flag->created_at?->diffForHumans() }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
