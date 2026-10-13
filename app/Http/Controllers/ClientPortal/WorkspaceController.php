<?php

declare(strict_types=1);

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Organization;
use App\Services\Agency\ClientPortalWorkspaceService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceController extends Controller
{
    public function __construct(
        private readonly ClientPortalWorkspaceService $workspaceService,
    ) {}

    public function show(Request $request, Agency $agency, Organization $organization): Response
    {
        $branding = $request->attributes->get('client_portal_branding');
        if (! is_array($branding)) {
            abort(500, 'Client portal branding is missing from the request.');
        }

        $user = $request->user();
        if ($user === null) {
            abort(403);
        }

        try {
            $organization = $this->workspaceService->resolveOrganizationForPortal($user, $agency, $organization);
        } catch (AuthorizationException $exception) {
            abort(403, $exception->getMessage());
        }

        return Inertia::render('Client/Portal/Workspace', [
            'agency' => [
                'id' => $agency->id,
                'name' => $agency->name,
            ],
            'organization' => [
                'id' => (string) $organization->id,
                'name' => $organization->name,
            ],
            'portalBranding' => $branding,
            'campaigns' => $this->workspaceService->campaignSummaries($organization),
            'reports' => $this->workspaceService->reportSummaries($organization),
        ]);
    }
}
