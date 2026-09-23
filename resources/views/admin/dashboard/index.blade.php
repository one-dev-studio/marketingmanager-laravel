@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="mt-1 text-gray-600">Welcome back, {{ auth('admin')->user()->name }}.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Organizations</p>
            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Organization::count() }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Users</p>
            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Campaigns</p>
            <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Campaign::count() }}</p>
        </div>
    </div>
</div>
@endsection
