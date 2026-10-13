<?php

declare(strict_types=1);

namespace App\Http\Controllers\ClientPortal;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Services\Agency\ClientPortalAccessService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PortalController extends Controller
{
    public function __construct(
        private readonly ClientPortalAccessService $accessService,
    ) {}

    public function home(Request $request, Agency $agency): Response
    {
        $branding = $request->attributes->get('client_portal_branding');
        if (! is_array($branding)) {
            abort(500, 'Client portal branding is missing from the request.');
        }

        $user = $request->user();
        if ($user === null) {
            abort(403);
        }

        return Inertia::render('Client/Portal/Home', [
            'agency' => [
                'id' => $agency->id,
                'name' => $agency->name,
            ],
            'portalBranding' => $branding,
            'organizations' => $this->accessService->accessibleClientOrganizations($user, $agency),
        ]);
    }
}
