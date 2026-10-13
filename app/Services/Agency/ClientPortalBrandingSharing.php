<?php

declare(strict_types=1);

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\Organization;
use Illuminate\Http\Request;

/**
 * Shares portal branding on organization Inertia pages when the org's agency portal is enabled.
 */
final class ClientPortalBrandingSharing
{
    public function __construct(
        private readonly AgencyPortalBrandingResolver $brandingResolver,
    ) {}

    /**
     * @return array<string, mixed>|null
     */
    public function forRequest(Request $request): ?array
    {
        if ($request->attributes->has('client_portal_branding')) {
            $branding = $request->attributes->get('client_portal_branding');
            if (is_array($branding)) {
                return $branding;
            }
        }

        $organizationId = $request->route('organizationId');
        if ($organizationId === null) {
            return null;
        }

        $organization = Organization::find($organizationId);
        if ($organization === null) {
            return null;
        }

        $agency = $organization->agencies()->first();
        if (! $agency instanceof Agency) {
            return null;
        }

        $branding = $this->brandingResolver->resolve($agency);
        if (! $branding['enabled']) {
            return null;
        }

        return [
            ...$branding,
            'agency_id' => $agency->id,
            'agency_name' => $agency->name,
            'portal_home_href' => route('client-portal.home', ['agency' => $agency->id]),
        ];
    }
}
