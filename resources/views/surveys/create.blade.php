@extends('layouts.app')
@section('page-title', 'Create survey')
@section('content')
<form method="POST" action="{{ route('main.surveys.store', ['organizationId' => $organizationId]) }}" class="max-w-xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <input name="title" required class="w-full rounded-md border-gray-300" placeholder="Title">
    <textarea name="description" class="w-full rounded-md border-gray-300" placeholder="Description"></textarea>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
</form>
@endsection
