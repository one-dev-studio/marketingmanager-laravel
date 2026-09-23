@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.organizations.index') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Back to organizations</a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">{{ $organization->name }}</h1>
        <p class="mt-1 text-gray-600">{{ $organization->slug }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Status</p>
            <p class="text-lg font-semibold text-gray-900">{{ $organization->status }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Timezone</p>
            <p class="text-lg font-semibold text-gray-900">{{ $organization->timezone }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Created</p>
            <p class="text-lg font-semibold text-gray-900">{{ $organization->created_at?->format('M j, Y') }}</p>
        </div>
    </div>
</div>
@endsection
