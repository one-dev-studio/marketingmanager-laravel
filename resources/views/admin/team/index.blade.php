@extends('layouts.admin')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-bold">Platform team</h1>
    <div class="bg-white border rounded-lg divide-y">
        @foreach($admins as $admin)
            <div class="p-4 flex justify-between">
                <div>
                    <p class="font-medium">{{ $admin->name }}</p>
                    <p class="text-sm text-gray-500">{{ $admin->email }}</p>
                </div>
                <a href="{{ route('admin.users.show', $admin) }}" class="text-blue-700 text-sm">View user</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
