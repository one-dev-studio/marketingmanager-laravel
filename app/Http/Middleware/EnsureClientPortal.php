<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Agency;
use App\Services\Agency\AgencyPortalBrandingResolver;
use App\Services\Agency\ClientPortalAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves agency client-portal branding and enforces portal access rules.
 */
class EnsureClientPortal
{
    public function __construct(
        private readonly AgencyPortalBrandingResolver $brandingResolver,
        private readonly ClientPortalAccessService $accessService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user === null) {
            abort(403, 'You must be authenticated to access the client portal.');
        }

        $agency = $request->route('agency');
        if (! $agency instanceof Agency) {
            $agency = Agency::find($agency);
        }

        if ($agency === null) {
            abort(404, 'Agency not found.');
        }

        $branding = $this->brandingResolver->resolve($agency);
        if (! $branding['enabled']) {
            abort(404, 'This client portal is not enabled.');
        }

        if (! $this->accessService->userCanAccessPortal($user, $agency)) {
            abort(403, 'You do not have access to this client portal.');
        }

        $request->attributes->set('client_portal_agency', $agency);
        $request->attributes->set('client_portal_branding', $branding);

        return $next($request);
    }
}
