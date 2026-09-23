@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Back to user</a>
        <h1 class="mt-2 text-2xl font-bold text-gray-900">Edit User</h1>
        <p class="mt-1 text-gray-600">{{ $user->email }}</p>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4 max-w-xl">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach(['active', 'inactive', 'suspended'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $user->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save Changes</button>
    </form>
</div>
@endsection
