@extends('layouts.app')
@section('page-title', 'Create landing page')
@section('content')
<form method="POST" action="{{ route('main.landing-pages.store', ['organizationId' => $organizationId]) }}" class="max-w-xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <input name="name" required class="w-full rounded-md border-gray-300" placeholder="Name">
    <input name="slug" class="w-full rounded-md border-gray-300" placeholder="slug">
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create & open builder</button>
</form>
@endsection
