<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandNameSuggestion;
use App\Services\Brand\BrandNameGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandNameGeneratorController extends Controller
{
    public function __construct(
        private BrandNameGeneratorService $generatorService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $this->authorize('viewAny', Brand::class);

        $suggestions = BrandNameSuggestion::where('organization_id', $organizationId)
            ->latest()
            ->limit(20)
            ->get();

        return view('brands.choose-name', [
            'title' => 'Brand name generator',
            'organizationId' => $organizationId,
            'suggestions' => $suggestions,
        ]);
    }

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'keywords' => ['required', 'array', 'min:1'],
            'keywords.*' => ['string', 'max:50'],
            'count' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $organization = $request->user()->primaryOrganization();
        $suggestions = $this->generatorService->generateSuggestions(
            $organization,
            $request->keywords,
            $request->count ?? 10
        );

        return response()->json([
            'success' => true,
            'data' => $suggestions,
            'message' => 'Brand name suggestions generated successfully.',
        ]);
    }

    public function checkDomain(Request $request): JsonResponse
    {
        $request->validate([
            'domain' => ['required', 'string', 'max:255'],
        ]);

        $available = $this->generatorService->checkDomainAvailability($request->domain);

        return response()->json([
            'success' => true,
            'data' => [
                'domain' => $request->domain,
                'available' => $available,
            ],
        ]);
    }

    public function checkSocialHandles(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $handles = $this->generatorService->checkSocialHandles($request->name);

        return response()->json([
            'success' => true,
            'data' => $handles,
        ]);
    }
}

