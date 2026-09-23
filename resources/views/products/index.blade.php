@extends('layouts.app')

@section('page-title', 'Products')

@section('content')
<div class="flex gap-6">
    <aside class="w-56 shrink-0">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Categories</h2>
            <a href="{{ route('main.products.index', ['organizationId' => $organizationId]) }}" class="block text-sm mb-2 {{ empty($filters['category_id']) ? 'text-blue-700 font-medium' : 'text-gray-600' }}">All</a>
            @foreach($categories as $category)
                <a href="{{ route('main.products.index', ['organizationId' => $organizationId, 'category_id' => $category->id]) }}"
                   class="block text-sm mb-1 {{ ($filters['category_id'] ?? null) == $category->id ? 'text-blue-700 font-medium' : 'text-gray-600' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </aside>
    <div class="flex-1 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Products</h1>
                <p class="text-sm text-gray-600">Catalog with images, SKUs, and variants</p>
            </div>
            @can('create', App\Models\Product::class)
                <a href="{{ route('main.products.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add Product</a>
            @endcan
        </div>
        <form class="flex gap-2" method="GET">
            <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name or SKU" class="rounded-md border-gray-300 flex-1">
            <button class="border px-3 rounded-md">Search</button>
        </form>
        @can('create', App\Models\Product::class)
        <form method="POST" action="{{ route('main.products.import', ['organizationId' => $organizationId]) }}" enctype="multipart/form-data" class="bg-white border rounded-lg p-3 flex items-center gap-3">
            @csrf
            <input type="file" name="file" accept=".csv,.xlsx,.xls" required class="text-sm">
            <label class="text-sm"><input type="checkbox" name="skip_duplicates" value="1" checked> Skip duplicates</label>
            <button class="text-sm text-blue-700">Import CSV/Excel</button>
        </form>
        @endcan
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($products as $product)
                <div class="bg-white border rounded-lg overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="" class="h-36 w-full object-cover">
                    @else
                        <div class="h-36 bg-gray-100"></div>
                    @endif
                    <div class="p-4">
                        <a href="{{ route('main.products.show', ['organizationId' => $organizationId, 'product' => $product]) }}" class="font-medium text-gray-900">{{ $product->name }}</a>
                        <p class="text-sm text-gray-500">{{ $product->sku }} · {{ $product->category?->name }}</p>
                        <p class="text-sm mt-1">${{ number_format($product->price, 2) }} · stock {{ $product->stock }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 text-center text-gray-500 bg-white border rounded-lg">No products yet.</div>
            @endforelse
        </div>
        {{ $products->links() }}
    </div>
</div>
@endsection
