<?php

declare(strict_types=1);

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\AI\GenerateLabelRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Services\AI\LabelInspirationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LabelInspirationController extends Controller
{
    public function __construct(
        private LabelInspirationService $labelService
    ) {}

    /**
     * Display label inspiration page
     */
    public function index(Request $request, string $organizationId): Response
    {
        $organization = $request->user()->primaryOrganization();

        $brands = Brand::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $products = Product::where('organization_id', $organization->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Tools/LabelInspiration', [
            'organizationId' => $organizationId,
            'brands' => $brands,
            'products' => $products,
        ]);
    }

    /**
     * Generate label inspiration variations
     */
    public function generateLabels(GenerateLabelRequest $request): JsonResponse
    {
        $organization = $request->user()->primaryOrganization();
        $product = $request->product_id ? Product::findOrFail($request->product_id) : null;
        $brand = $request->brand_id ? Brand::findOrFail($request->brand_id) : null;

        $labels = $this->labelService->generateLabels(
            $organization,
            $request->user(),
            $product,
            $brand,
            $request->context ?? '',
            $request->variation_count ?? 5,
            $request->only(['model', 'temperature'])
        );

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'count' => count($labels),
            ],
        ]);
    }
}
