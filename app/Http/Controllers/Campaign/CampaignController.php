<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\CreateCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Http\Resources\Campaign\CampaignResource;
use App\Models\Campaign;
use App\Services\Campaign\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignService $campaignService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $brandId = $request->query('brand_id');

        $campaigns = Campaign::where('organization_id', $organizationId)
            ->forBrand($brandId)
            ->with(['channels', 'organization', 'creator', 'brand'])
            ->paginate();

        if ($request->expectsJson()) {
            return CampaignResource::collection($campaigns);
        }

        return view('campaigns.index', [
            'organizationId' => $organizationId,
            'campaigns' => $campaigns,
        ]);
    }

    public function create(Request $request, string $organizationId)
    {
        $brandId = $request->query('brand_id') ?? $request->query('brandId');
        
        $brands = \App\Models\Brand::where('organization_id', $organizationId)->get();
        $products = \App\Models\Product::where('organization_id', $organizationId)
            ->when($brandId, fn($q) => $q->where('brand_id', $brandId))
            ->get();
        $channels = \App\Models\Channel::where('organization_id', $organizationId)
            ->where('status', 'active')
            ->get();

        return view('campaigns.create', [
            'organizationId' => $organizationId,
            'brandId' => $brandId,
            'brands' => $brands,
            'products' => $products,
            'channels' => $channels,
        ]);
    }

    public function store(CreateCampaignRequest $request, ?string $organizationId = null)
    {
        $resolvedOrganizationId = (int) (
            $organizationId ?? $request->route('organizationId') ?? $request->user()->primaryOrganization()?->id
        );

        $campaign = $this->campaignService->createCampaign(
            [
                ...$request->validated(),
                'organization_id' => $resolvedOrganizationId,
            ],
            $request->user()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign created successfully.',
            ], 201);
        }

        return redirect()
            ->route('main.campaigns.show', ['organizationId' => $resolvedOrganizationId, 'campaign' => $campaign])
            ->with('success', 'Campaign created successfully.');
    }

    public function show(Request $request, string $organizationId, Campaign $campaign)
    {
        $this->authorize('view', $campaign);

        $campaign->load(['channels', 'goals', 'scheduledPosts']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
            ]);
        }

        return view('campaigns.show', [
            'campaign' => $campaign,
            'organizationId' => $organizationId,
        ]);
    }

    public function update(UpdateCampaignRequest $request, string $organizationId, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $campaign = $this->campaignService->updateCampaign(
            $campaign,
            $request->validated()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign updated successfully.',
            ]);
        }

        return redirect()
            ->route('main.campaigns.show', ['organizationId' => $organizationId, 'campaign' => $campaign])
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Request $request, string $organizationId, Campaign $campaign)
    {
        $this->authorize('delete', $campaign);

        $this->campaignService->deleteCampaign($campaign);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Campaign deleted successfully.',
            ]);
        }

        return redirect()
            ->route('main.campaigns.index', ['organizationId' => $organizationId])
            ->with('success', 'Campaign deleted successfully.');
    }

    public function submitForReview(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        try {
            $campaign->submitForReview();
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign submitted for review successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function publish(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        $this->campaignService->publishCampaign($campaign);

        return response()->json([
            'success' => true,
            'message' => 'Campaign published successfully.',
        ]);
    }

    public function deactivate(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        try {
            $campaign->deactivate();
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign deactivated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function reactivate(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        try {
            $campaign->reactivate();
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign reactivated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function pause(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        try {
            $campaign->pause();
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign paused successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function resume(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        try {
            $campaign->resume();
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign resumed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function complete(Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        try {
            $campaign->complete();
            return response()->json([
                'success' => true,
                'data' => new CampaignResource($campaign),
                'message' => 'Campaign completed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function clone(Request $request, Campaign $campaign): JsonResponse
    {
        $this->authorize('view', $campaign);

        $clonedCampaign = $campaign->clone($request->user());

        return response()->json([
            'success' => true,
            'data' => new CampaignResource($clonedCampaign),
            'message' => 'Campaign cloned successfully.',
        ], 201);
    }

    public function attachProducts(Request $request, Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        $campaign->products()->syncWithoutDetaching($request->product_ids);

        return response()->json([
            'success' => true,
            'message' => 'Products linked to campaign successfully.',
        ]);
    }

    public function detachProducts(Request $request, Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        $campaign->products()->detach($request->product_ids);

        return response()->json([
            'success' => true,
            'message' => 'Products unlinked from campaign successfully.',
        ]);
    }

    public function generatePlan(Request $request): JsonResponse
    {
        $request->validate([
            'goal' => ['required', 'string'],
            'goal_type' => ['required', 'string'],
            'channel_ids' => ['required', 'array', 'min:1'],
            'channel_ids.*' => ['exists:channels,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'product_id' => ['nullable', 'exists:products,id'],
            'goal_prompt' => ['nullable', 'url'],
        ]);

        try {
            $result = $this->campaignService->generateCampaignPlan(
                $request->all(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function generateContent(Request $request, Campaign $campaign): JsonResponse
    {
        $this->authorize('update', $campaign);

        $request->validate([
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['exists:channels,id'],
        ]);

        try {
            $result = $this->campaignService->generateCampaignContent(
                $campaign,
                $request->user(),
                $request->channels
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAISuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'brand_id' => ['nullable', 'exists:brands,id'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        try {
            $suggestions = $this->campaignService->getCampaignSuggestions(
                $request->user(),
                $request->brand_id,
                $request->product_id
            );

            return response()->json([
                'success' => true,
                'data' => $suggestions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

