@extends('layouts.agency')
@section('content')
<form method="POST" action="{{ route('agency.clients.store', $agency) }}" class="max-w-xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <h1 class="text-2xl font-semibold">Add client</h1>
    <input name="name" required class="w-full rounded-md border-gray-300" placeholder="Organization name">
    <input name="slug" class="w-full rounded-md border-gray-300" placeholder="slug (optional)">
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create & associate</button>
</form>
@endsection
