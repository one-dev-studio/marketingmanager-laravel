@extends('layouts.admin')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold">Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">New package</a>
    </div>
    <div class="bg-white border rounded-lg divide-y">
        @forelse($packages as $package)
            <div class="p-4 flex justify-between">
                <div>
                    <h2 class="font-medium">{{ $package->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $package->billing_cycle }} · {{ $package->price }} · {{ $package->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
                <div class="flex gap-3 text-sm">
                    <a href="{{ route('admin.packages.edit', $package) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.packages.destroy', $package) }}">@csrf @method('DELETE')<button class="text-red-600">Delete</button></form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No packages.</div>
        @endforelse
    </div>
</div>
@endsection
