@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route('admin.packages.store') }}" class="max-w-xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <h1 class="text-2xl font-bold">Create package</h1>
    @include('admin.packages._fields')
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
</form>
@endsection
