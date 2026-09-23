@extends('layouts.app')

@section('page-title', $brand->name)

@section('content')
<div class="space-y-6 max-w-3xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $brand->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $brand->status }}</p>
        </div>
        <div class="flex items-center gap-3">
            @can('update', $brand)
                <a href="{{ route('main.brands.edit', ['organizationId' => $organizationId, 'brand' => $brand]) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Edit</a>
            @endcan
            @can('delete', $brand)
                <form method="POST"
                      action="{{ route('main.brands.destroy', ['organizationId' => $organizationId, 'brand' => $brand]) }}"
                      onsubmit="return confirm('Delete this brand? This cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        <div>
            <h2 class="text-sm font-medium text-gray-500">Summary</h2>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $brand->summary ?: '—' }}</p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Guidelines</h2>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $brand->guidelines ?: '—' }}</p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Tone of voice</h2>
            <p class="mt-1 text-gray-900">{{ $brand->tone_of_voice ?: '—' }}</p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Audience</h2>
            <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $brand->audience ?: '—' }}</p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Keywords to use</h2>
            <p class="mt-1 text-gray-900">{{ implode(', ', $brand->keywords ?? []) ?: '—' }}</p>
        </div>
        <div>
            <h2 class="text-sm font-medium text-gray-500">Keywords to avoid</h2>
            <p class="mt-1 text-gray-900">{{ implode(', ', $brand->avoid_keywords ?? []) ?: '—' }}</p>
        </div>
    </div>

    <a href="{{ route('main.brands.index', ['organizationId' => $organizationId]) }}" class="text-sm text-blue-600 hover:text-blue-800">Back to brands</a>
</div>
@endsection
