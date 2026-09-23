<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\ImportProductsRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $query = Product::where('organization_id', $organizationId)
            ->with(['brand', 'category', 'images', 'variants'])
            ->withCount('variants');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->paginate();
        $categories = ProductCategory::where('organization_id', $organizationId)->orderBy('name')->get();

        if ($this->wantsJson($request)) {
            return ProductResource::collection($products);
        }

        return view('products.index', [
            'title' => 'Products',
            'organizationId' => $organizationId,
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category_id', 'status']),
        ]);
    }

    public function create(Request $request, string $organizationId)
    {
        $this->authorize('create', Product::class);

        return view('products.form', [
            'title' => 'Add Product',
            'organizationId' => $organizationId,
            'product' => null,
            'categories' => ProductCategory::where('organization_id', $organizationId)->orderBy('name')->get(),
            'brands' => Brand::where('organization_id', $organizationId)->orderBy('name')->get(),
        ]);
    }

    public function store(CreateProductRequest $request, string $organizationId)
    {
        $product = $this->productService->createProduct(
            $request->validated(),
            $request->user()
        );

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ProductResource($product->load(['images', 'variants'])),
                'message' => 'Product created successfully.',
            ], 201);
        }

        return redirect()
            ->route('main.products.show', ['organizationId' => $organizationId, 'product' => $product])
            ->with('success', 'Product created.');
    }

    public function show(Request $request, string $organizationId, Product $product)
    {
        $this->authorize('view', $product);
        $product->load(['brand', 'category', 'images', 'variants']);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ProductResource($product),
            ]);
        }

        return view('products.show', [
            'title' => $product->name,
            'organizationId' => $organizationId,
            'product' => $product,
        ]);
    }

    public function edit(Request $request, string $organizationId, Product $product)
    {
        $this->authorize('update', $product);

        return view('products.form', [
            'title' => 'Edit Product',
            'organizationId' => $organizationId,
            'product' => $product->load(['images', 'variants']),
            'categories' => ProductCategory::where('organization_id', $organizationId)->orderBy('name')->get(),
            'brands' => Brand::where('organization_id', $organizationId)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, string $organizationId, Product $product)
    {
        $product = $this->productService->updateProduct(
            $product,
            $request->validated()
        );

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ProductResource($product->load(['images', 'variants'])),
                'message' => 'Product updated successfully.',
            ]);
        }

        return redirect()
            ->route('main.products.show', ['organizationId' => $organizationId, 'product' => $product])
            ->with('success', 'Product updated.');
    }

    public function destroy(Request $request, string $organizationId, Product $product)
    {
        $this->authorize('delete', $product);
        $this->productService->deleteProduct($product);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
            ]);
        }

        return redirect()
            ->route('main.products.index', ['organizationId' => $organizationId])
            ->with('success', 'Product deleted.');
    }

    public function import(ImportProductsRequest $request, string $organizationId)
    {
        try {
            $result = $this->productService->importProductsFromFile(
                $request->file('file'),
                $request->user(),
                [
                    'skip_duplicates' => $request->boolean('skip_duplicates', true),
                    'update_existing' => $request->boolean('update_existing', false),
                ]
            );

            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => "Imported {$result['imported']} products successfully.",
                ]);
            }

            return back()->with('success', "Imported {$result['imported']} products.");
        } catch (\Exception $e) {
            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStock(Request $request, string $organizationId, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
            'operation' => ['nullable', 'in:set,add,subtract'],
        ]);

        $product = $this->productService->updateStock(
            $product,
            $request->quantity,
            $request->operation ?? 'set'
        );

        return response()->json([
            'success' => true,
            'data' => new ProductResource($product),
            'message' => 'Stock updated successfully.',
        ]);
    }
}
