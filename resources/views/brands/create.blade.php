@extends('layouts.app')

@section('page-title', 'Create Brand')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Create Brand</h1>
        <p class="mt-1 text-sm text-gray-600">Add guidelines and tone of voice used by AI content tools</p>
    </div>

    <form method="POST" action="{{ route('main.brands.store', ['organizationId' => $organizationId]) }}" enctype="multipart/form-data">
        @csrf
        @include('brands._form')
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save brand</button>
            <a href="{{ route('main.brands.index', ['organizationId' => $organizationId]) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
        </div>
    </form>
</div>
@endsection
