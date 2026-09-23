@extends('layouts.app')

@section('page-title', $product->name)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500">{{ $product->sku }} · {{ $product->status }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('main.products.edit', ['organizationId' => $organizationId, 'product' => $product]) }}" class="text-blue-600">Edit</a>
            <form method="POST" action="{{ route('main.products.destroy', ['organizationId' => $organizationId, 'product' => $product]) }}" onsubmit="return confirm('Delete this product?');">
                @csrf @method('DELETE')
                <button class="text-red-600">Delete</button>
            </form>
        </div>
    </div>
    <div class="bg-white border rounded-lg p-6 space-y-2">
        <p>{{ $product->description }}</p>
        <p class="text-sm">Price ${{ number_format($product->price, 2) }} · Stock {{ $product->stock }} · {{ $product->category?->name }}</p>
    </div>
    <div class="bg-white border rounded-lg p-6">
        <h2 class="font-medium mb-3">Images</h2>
        <div class="flex gap-2 flex-wrap">
            @foreach($product->images as $image)
                <img src="{{ $image->url }}" class="h-20 w-20 object-cover rounded" alt="">
            @endforeach
        </div>
        <form class="mt-4 flex gap-2" method="POST" enctype="multipart/form-data" action="{{ route('main.products.images.store', ['organizationId' => $organizationId, 'product' => $product]) }}">
            @csrf
            <input type="file" name="image" required>
            <button class="text-sm text-blue-700">Upload</button>
        </form>
    </div>
    <div class="bg-white border rounded-lg p-6">
        <h2 class="font-medium mb-3">Variants</h2>
        <ul class="text-sm space-y-1">
            @foreach($product->variants as $variant)
                <li>{{ $variant->name ?? $variant->sku }} · {{ $variant->sku }}</li>
            @endforeach
        </ul>
        <form method="POST" action="{{ route('main.products.variants.store', ['organizationId' => $organizationId, 'product' => $product]) }}" class="mt-4 grid grid-cols-3 gap-2">
            @csrf
            <input name="name" placeholder="Name" required class="rounded-md border-gray-300">
            <input name="sku" placeholder="SKU" required class="rounded-md border-gray-300">
            <button class="text-sm text-blue-700">Add variant</button>
        </form>
    </div>
</div>
@endsection
