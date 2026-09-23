@extends('layouts.auth')

@section('title', 'Admin Sign In')
@section('subtitle', 'Platform administrator access')

@section('content')
<form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Admin Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input id="password" name="password" type="password" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>

    <div class="flex items-center">
        <label class="flex items-center text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
            <span class="ml-2">Remember me</span>
        </label>
    </div>

    <button type="submit" class="w-full rounded-md bg-gray-900 px-4 py-2 text-white hover:bg-gray-800">
        Admin Sign In
    </button>
</form>
@endsection
