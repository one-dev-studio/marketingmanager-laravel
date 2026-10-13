<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\ConnectStorageSourceRequest;
use App\Models\Organization;
use App\Services\Organization\StorageSourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Organization Storage Source Controller
 * Handles cloud storage integration management
 * Requires organization admin access
 */
class StorageSourceController extends Controller
{
    public function __construct(
        private StorageSourceService $storageService
    ) {}

    /**
     * Display storage sources
     */
    public function index(Request $request, string $organizationId): Response
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $sources = $this->storageService->getStorageSources($organization);
        $quota = $this->storageService->quota($organization);

        $providerLabels = [
            's3' => 'Amazon S3',
            'google_drive' => 'Google Drive',
            'dropbox' => 'Dropbox',
        ];

        $sourcesByProvider = collect($sources)->keyBy('provider');

        $providers = [];
        foreach ($providerLabels as $provider => $label) {
            $source = $sourcesByProvider->get($provider);
            $providers[] = [
                'provider' => $provider,
                'label' => $label,
                'is_connected' => (bool) ($source['is_connected'] ?? false),
            ];
        }

        return Inertia::render('Organization/StorageSources/Index', [
            'organizationId' => $organizationId,
            'providers' => $providers,
            'quota' => [
                'used_human' => $quota['used_human'],
                'limit_human' => $quota['limit_human'],
            ],
        ]);
    }

    public function connect(ConnectStorageSourceRequest $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->storageService->connectStorageSource(
            $organization,
            $request->input('provider'),
            $request->validated()
        );

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Storage source connected successfully.'], 201);
        }

        return back()->with('success', 'Storage source connected.');
    }

    public function disconnect(Request $request, string $organizationId, string $provider)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);
        $this->storageService->disconnectStorageSource($organization, $provider);

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Storage source disconnected successfully.']);
        }

        return back()->with('success', 'Disconnected.');
    }

    /**
     * Get storage source details
     */
    public function show(Request $request, string $organizationId, string $provider): JsonResponse
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('view', $organization);

        $credentials = $this->storageService->getStorageSourceCredentials($organization, $provider);

        if (! $credentials) {
            return response()->json([
                'success' => false,
                'message' => 'Storage source not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'provider' => $provider,
                'name' => $credentials['name'] ?? $provider,
                'is_connected' => ! empty($credentials['access_token']),
            ],
        ]);
    }

    /**
     * Update storage source settings
     */
    public function updateSettings(Request $request, string $organizationId, string $provider): JsonResponse
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $request->validate([
            'settings' => ['required', 'array'],
        ]);

        $this->storageService->updateStorageSourceSettings(
            $organization,
            $provider,
            $request->input('settings')
        );

        return response()->json([
            'success' => true,
            'message' => 'Storage source settings updated successfully.',
        ]);
    }
}
