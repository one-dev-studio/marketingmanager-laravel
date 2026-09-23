@extends('layouts.app')

@section('page-title', 'Brands')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Brands</h1>
            <p class="mt-1 text-sm text-gray-600">Manage brand guidelines, tone of voice, and keywords</p>
        </div>
        @can('create', App\Models\Brand::class)
            <div class="flex items-center gap-3">
                <a href="{{ route('main.brands.choose-name', ['organizationId' => $organizationId]) }}"
                   class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                    Choose name
                </a>
                <a href="{{ route('main.brands.create', ['organizationId' => $organizationId]) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Add Brand
                </a>
            </div>
        @endcan
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @forelse($brands as $brand)
            <div class="p-4 border-b border-gray-100 flex items-start justify-between gap-4 hover:bg-gray-50">
                <div class="min-w-0">
                    <a href="{{ route('main.brands.show', ['organizationId' => $organizationId, 'brand' => $brand]) }}"
                       class="font-medium text-gray-900 hover:text-blue-700">{{ $brand->name }}</a>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $brand->status }}
                        @if($brand->summary)
                            · {{ \Illuminate\Support\Str::limit($brand->summary, 120) }}
                        @endif
                    </p>
                    @if($brand->guidelines)
                        <p class="text-sm text-gray-400 mt-1">{{ \Illuminate\Support\Str::limit($brand->guidelines, 160) }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    @can('update', $brand)
                        <a href="{{ route('main.brands.edit', ['organizationId' => $organizationId, 'brand' => $brand]) }}"
                           class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
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
        @empty
            <div class="p-8 text-center text-gray-500">No brands yet.</div>
        @endforelse
    </div>

    {{ $brands->links() }}
</div>
@endsection
