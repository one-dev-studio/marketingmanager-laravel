<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use App\Models\Organization;
use App\Services\Brand\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(
        private BrandService $brandService
    ) {}

    public function index(Request $request, ?string $organizationId = null)
    {
        $organizationId = $this->resolveOrganizationId($request, $organizationId);

        $this->authorize('viewAny', Brand::class);

        $brands = Brand::where('organization_id', $organizationId)
            ->with(['organization', 'assets'])
            ->withCount('products')
            ->paginate();

        if ($this->wantsJson($request)) {
            return BrandResource::collection($brands);
        }

        return view('brands.index', [
            'title' => 'Brands',
            'organizationId' => $organizationId,
            'brands' => $brands,
        ]);
    }

    public function create(Request $request, string $organizationId)
    {
        $this->authorize('create', Brand::class);

        return view('brands.create', [
            'title' => 'Create Brand',
            'organizationId' => $organizationId,
            'prefillName' => $request->query('name'),
        ]);
    }

    public function store(CreateBrandRequest $request, ?string $organizationId = null)
    {
        $organizationId = $this->resolveOrganizationId($request, $organizationId);

        $brand = $this->brandService->createBrand(
            $request->validated(),
            $request->user(),
            (int) $organizationId
        );

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new BrandResource($brand->load('assets')),
                'message' => 'Brand created successfully.',
            ], 201);
        }

        return redirect()
            ->route('main.brands.show', ['organizationId' => $organizationId, 'brand' => $brand])
            ->with('success', 'Brand created successfully.');
    }

    public function show(Request $request, string $organizationId, Brand $brand)
    {
        $this->authorize('view', $brand);

        $brand->load(['organization', 'assets', 'products']);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new BrandResource($brand),
            ]);
        }

        return view('brands.show', [
            'title' => $brand->name,
            'organizationId' => $organizationId,
            'brand' => $brand,
        ]);
    }

    public function edit(Request $request, string $organizationId, Brand $brand)
    {
        $this->authorize('update', $brand);

        return view('brands.edit', [
            'title' => 'Edit Brand',
            'organizationId' => $organizationId,
            'brand' => $brand,
            'prefillName' => null,
        ]);
    }

    public function update(UpdateBrandRequest $request, string $organizationId, Brand $brand)
    {
        $brand = $this->brandService->updateBrand(
            $brand,
            $request->validated()
        );

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new BrandResource($brand->load('assets')),
                'message' => 'Brand updated successfully.',
            ]);
        }

        return redirect()
            ->route('main.brands.show', ['organizationId' => $organizationId, 'brand' => $brand])
            ->with('success', 'Brand updated successfully.');
    }

    public function destroy(Request $request, string $organizationId, Brand $brand)
    {
        $this->authorize('delete', $brand);

        $this->brandService->deleteBrand($brand);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully.',
            ]);
        }

        return redirect()
            ->route('main.brands.index', ['organizationId' => $organizationId])
            ->with('success', 'Brand deleted successfully.');
    }

    public function generateConcept(Request $request, string $organizationId): JsonResponse
    {
        $this->authorize('create', Brand::class);

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'keywords' => ['nullable', 'array'],
            'keywords.*' => ['string', 'max:100'],
        ]);

        $organization = Organization::findOrFail($organizationId);

        $concept = $this->brandService->generateConcept(
            $organization,
            $request->user(),
            $validated
        );

        return response()->json([
            'success' => true,
            'data' => $concept,
        ]);
    }

    /**
     * Display brand assets page with brand guidelines and assets
     */
    public function brandAssets(Request $request, string $organizationId)
    {
        $brandId = $request->query('brandId');

        if (!$brandId) {
            abort(400, 'Brand ID is required.');
        }

        $brand = Brand::where('organization_id', $organizationId)
            ->where('id', $brandId)
            ->firstOrFail();

        $this->authorize('view', $brand);

        $brand->load('assets');

        $guidelines = $this->brandService->getBrandGuidelines($brand);
        $assetsGrouped = $this->brandService->getAssetsGroupedByType($brand);

        return view('brand-assets.index', [
            'organizationId' => $organizationId,
            'brand' => $brand,
            'guidelines' => $guidelines,
            'assetsGrouped' => $assetsGrouped,
        ]);
    }

    private function resolveOrganizationId(Request $request, ?string $organizationId): ?string
    {
        if ($organizationId) {
            return $organizationId;
        }

        $fromRoute = $request->route('organizationId');
        if ($fromRoute) {
            return (string) $fromRoute;
        }

        return $request->user()?->primaryOrganization()?->id
            ? (string) $request->user()->primaryOrganization()->id
            : null;
    }
}
