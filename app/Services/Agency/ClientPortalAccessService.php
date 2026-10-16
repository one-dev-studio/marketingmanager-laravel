<?php

declare(strict_types=1);

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\Organization;
use App\Models\User;

/**
 * Determines whether a user may view an agency's client portal.
 */
final class ClientPortalAccessService
{
    public function userCanAccessPortal(User $user, Agency $agency): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAgencyMember($agency->id)) {
            return true;
        }

        $organizationIds = $user->organizations()->pluck('organizations.id');

        if ($organizationIds->isEmpty()) {
            return false;
        }

        return $agency->clientOrganizations()
            ->whereIn('organizations.id', $organizationIds)
            ->exists();
    }

    /**
     * @return list<array{id: string, name: string, dashboard_href: string, workspace_href: string}>
     */
    public function accessibleClientOrganizations(User $user, Agency $agency): array
    {
        $query = $agency->clientOrganizations()->orderBy('organizations.name');

        if (! $user->isAdmin() && ! $user->isAgencyMember($agency->id)) {
            $organizationIds = $user->organizations()->pluck('organizations.id');
            $query->whereIn('organizations.id', $organizationIds);
        }

        return $query->get(['organizations.id', 'organizations.name'])
            ->map(static fn (Organization $organization): array => [
                'id' => (string) $organization->id,
                'name' => $organization->name,
                'dashboard_href' => route('main.dashboard', ['organizationId' => $organization->id]),
                'workspace_href' => route('client-portal.workspace', [
                    'agency' => $agency->id,
                    'organization' => $organization->id,
                ]),
            ])
            ->values()
            ->all();
    }
}
