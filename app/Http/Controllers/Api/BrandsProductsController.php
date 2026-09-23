<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Brand\BrandController as BaseBrandController;
use App\Http\Controllers\Product\ProductController as BaseProductController;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Brands & Products API Controller
 */
class BrandsProductsController extends Controller
{
    public function __construct(
        private BaseBrandController $brandController,
        private BaseProductController $productController
    ) {}

    /**
     * List brands
     */
    public function brands(Request $request): AnonymousResourceCollection
    {
        $request->headers->set('Accept', 'application/json');

        return $this->brandController->index($request);
    }

    /**
     * Get brand
     */
    public function getBrand(Request $request, $brandId): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');
        $brand = \App\Models\Brand::findOrFail($brandId);

        return $this->brandController->show($request, (string) $brand->organization_id, $brand);
    }

    /**
     * Create brand
     */
    public function createBrand(Request $request): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        return $this->brandController->store($request);
    }

    /**
     * Update brand
     */
    public function updateBrand(Request $request, $brandId): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');
        $brand = \App\Models\Brand::findOrFail($brandId);

        return $this->brandController->update($request, (string) $brand->organization_id, $brand);
    }

    /**
     * Delete brand
     */
    public function deleteBrand(Request $request, $brandId): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');
        $brand = \App\Models\Brand::findOrFail($brandId);

        return $this->brandController->destroy($request, (string) $brand->organization_id, $brand);
    }

    /**
     * List products
     */
    public function products(Request $request): AnonymousResourceCollection
    {
        return $this->productController->index($request);
    }

    /**
     * Get product
     */
    public function getProduct(Request $request, $productId): JsonResponse
    {
        $product = \App\Models\Product::findOrFail($productId);
        return $this->productController->show($product);
    }

    /**
     * Create product
     */
    public function createProduct(Request $request): JsonResponse
    {
        return $this->productController->store($request);
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, $productId): JsonResponse
    {
        $product = \App\Models\Product::findOrFail($productId);
        return $this->productController->update($request, $product);
    }

    /**
     * Delete product
     */
    public function deleteProduct(Request $request, $productId): JsonResponse
    {
        $product = \App\Models\Product::findOrFail($productId);
        return $this->productController->destroy($request, $product);
    }
}

