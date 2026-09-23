@extends('layouts.app')

@section('page-title', 'Edit Brand')

@section('content')
<div class="space-y-6 max-w-3xl">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Edit {{ $brand->name }}</h1>
        <p class="mt-1 text-sm text-gray-600">Update guidelines and tone of voice used by AI content tools</p>
    </div>

    <form method="POST" action="{{ route('main.brands.update', ['organizationId' => $organizationId, 'brand' => $brand]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('brands._form')
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Save changes</button>
            <a href="{{ route('main.brands.show', ['organizationId' => $organizationId, 'brand' => $brand]) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
        </div>
    </form>
</div>
@endsection
