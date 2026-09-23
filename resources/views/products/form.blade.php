@extends('layouts.app')

@section('page-title', $product ? 'Edit Product' : 'Add Product')

@section('content')
<div class="max-w-2xl space-y-6">
    <h1 class="text-2xl font-semibold">{{ $product ? 'Edit Product' : 'Add Product' }}</h1>
    <form method="POST" enctype="multipart/form-data"
          action="{{ $product ? route('main.products.update', ['organizationId' => $organizationId, 'product' => $product]) : route('main.products.store', ['organizationId' => $organizationId]) }}"
          class="bg-white border rounded-lg p-6 space-y-4">
        @csrf
        @if($product) @method('PUT') @endif
        <input name="name" required value="{{ old('name', $product?->name) }}" placeholder="Name" class="w-full rounded-md border-gray-300">
        <input name="sku" required value="{{ old('sku', $product?->sku) }}" placeholder="SKU" class="w-full rounded-md border-gray-300">
        <textarea name="description" rows="4" placeholder="Description" class="w-full rounded-md border-gray-300">{{ old('description', $product?->description) }}</textarea>
        <div class="grid grid-cols-2 gap-4">
            <input type="number" step="0.01" name="price" required value="{{ old('price', $product?->price) }}" placeholder="Price" class="rounded-md border-gray-300">
            <input type="number" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}" placeholder="Stock" class="rounded-md border-gray-300">
        </div>
        <select name="category_id" class="w-full rounded-md border-gray-300">
            <option value="">Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="brand_id" class="w-full rounded-md border-gray-300">
            <option value="">Brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
            @endforeach
        </select>
        <select name="status" class="w-full rounded-md border-gray-300">
            @foreach(['draft','active','inactive'] as $status)
                <option value="{{ $status }}" @selected(old('status', $product?->status ?? 'draft') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <input type="file" name="image" accept="image/*">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
    </form>
</div>
@endsection
