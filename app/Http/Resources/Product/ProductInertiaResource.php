<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use App\Models\Product;
use Illuminate\Http\Request;

final class ProductInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Product $product, Request $request): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'brand_name' => $product->relationLoaded('brand') && $product->brand !== null
                ? $product->brand->name
                : null,
            'category_name' => $product->relationLoaded('category') && $product->category !== null
                ? $product->category->name
                : null,
            'can' => [
                'update' => $request->user()?->can('update', $product) ?? false,
                'delete' => $request->user()?->can('delete', $product) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function detail(Product $product, Request $request): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => $product->status,
            'brand_id' => $product->brand_id,
            'category_id' => $product->category_id,
            'image' => $product->image,
            'brand_name' => $product->relationLoaded('brand') && $product->brand !== null
                ? $product->brand->name
                : null,
            'category_name' => $product->relationLoaded('category') && $product->category !== null
                ? $product->category->name
                : null,
            'variants_count' => $product->variants_count ?? ($product->relationLoaded('variants') ? $product->variants->count() : 0),
            'can' => [
                'update' => $request->user()?->can('update', $product) ?? false,
                'delete' => $request->user()?->can('delete', $product) ?? false,
            ],
        ];
    }
}
