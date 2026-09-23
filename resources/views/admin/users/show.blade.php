@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Back to users</a>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            <p class="mt-1 text-gray-600">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm text-blue-600 hover:text-blue-800">Edit</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">User Type</p>
            <p class="text-lg font-semibold text-gray-900">{{ $user->user_type }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-lg font-semibold text-gray-900">{{ $user->status }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Email Verified</p>
            <p class="text-lg font-semibold text-gray-900">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 p-4">
        <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
        @if($activityLogs->isEmpty())
            <p class="mt-2 text-sm text-gray-500">No activity logs recorded.</p>
        @else
            <ul class="mt-4 space-y-2">
                @foreach($activityLogs as $log)
                    <li class="text-sm text-gray-600">{{ $log->action ?? 'Activity' }} · {{ $log->created_at?->diffForHumans() }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
