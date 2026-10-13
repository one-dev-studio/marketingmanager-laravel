<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\ImportProductsRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductInertiaResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $query = Product::where('organization_id', $organizationId)
            ->with(['brand', 'category'])
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
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('sku', 'like', '%'.$request->search.'%');
            });
        }

        $products = $query->paginate();
        $categories = ProductCategory::where('organization_id', $organizationId)->orderBy('name')->get();

        if ($this->wantsJson($request)) {
            return ProductResource::collection($products);
        }

        return Inertia::render('Products/Index', [
            'organizationId' => $organizationId,
            'products' => $products->through(
                fn (Product $product) => ProductInertiaResource::listItem($product, $request)
            ),
            'categories' => $categories->map(fn (ProductCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
            ])->values()->all(),
            'filters' => $request->only(['search', 'category_id', 'status']),
            'can' => [
                'create' => $request->user()?->can('create', Product::class) ?? false,
            ],
        ]);
    }

    public function create(Request $request, string $organizationId): Response
    {
        $this->authorize('create', Product::class);

        return Inertia::render('Products/Create', [
            'organizationId' => $organizationId,
            'categories' => $this->categoryOptions($organizationId),
            'brands' => $this->brandOptions($organizationId),
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

        return Inertia::render('Products/Show', [
            'organizationId' => $organizationId,
            'product' => ProductInertiaResource::detail($product, $request),
        ]);
    }

    public function edit(Request $request, string $organizationId, Product $product): Response
    {
        $this->authorize('update', $product);

        $product->load(['brand', 'category', 'images', 'variants']);
        $product->loadCount('variants');

        return Inertia::render('Products/Edit', [
            'organizationId' => $organizationId,
            'product' => ProductInertiaResource::detail($product, $request),
            'categories' => $this->categoryOptions($organizationId),
            'brands' => $this->brandOptions($organizationId),
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

    /**
     * @return list<array{id: int, name: string}>
     */
    private function categoryOptions(string $organizationId): array
    {
        return ProductCategory::where('organization_id', $organizationId)
            ->orderBy('name')
            ->get()
            ->map(fn (ProductCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    private function brandOptions(string $organizationId): array
    {
        return Brand::where('organization_id', $organizationId)
            ->orderBy('name')
            ->get()
            ->map(fn (Brand $brand) => [
                'id' => $brand->id,
                'name' => $brand->name,
            ])
            ->values()
            ->all();
    }
}
