<?php

declare(strict_types=1);

namespace App\Services\Agency;

use App\Models\Agency;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Read-only client-portal workspace data (campaigns and reports).
 */
final class ClientPortalWorkspaceService
{
    public function __construct(
        private readonly ClientPortalAccessService $accessService,
    ) {}

    /**
     * @throws AuthorizationException
     */
    public function resolveOrganizationForPortal(User $user, Agency $agency, Organization $organization): Organization
    {
        if (! $this->accessService->userCanAccessPortal($user, $agency)) {
            throw new AuthorizationException('You cannot access this client portal.');
        }

        $isClientOrg = $agency->clientOrganizations()
            ->where('organizations.id', $organization->id)
            ->exists();

        if (! $isClientOrg) {
            throw new AuthorizationException('This workspace is not linked to the agency portal.');
        }

        if (! $user->isAdmin() && ! $user->isAgencyMember($agency->id)) {
            if (! $user->organizations()->where('organizations.id', $organization->id)->exists()) {
                throw new AuthorizationException('You do not have access to this workspace.');
            }
        }

        return $organization;
    }

    /**
     * @return list<array{id: int, name: string, status: string, start_date: string|null, end_date: string|null}>
     */
    public function campaignSummaries(Organization $organization): array
    {
        return Campaign::query()
            ->where('organization_id', $organization->id)
            ->orderByDesc('updated_at')
            ->limit(25)
            ->get(['id', 'name', 'status', 'start_date', 'end_date'])
            ->map(static fn (Campaign $campaign): array => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'status' => (string) $campaign->status,
                'start_date' => $campaign->start_date?->toDateString(),
                'end_date' => $campaign->end_date?->toDateString(),
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{id: int, name: string, type: string|null, last_generated_at: string|null}>
     */
    public function reportSummaries(Organization $organization): array
    {
        return Report::query()
            ->where('organization_id', $organization->id)
            ->orderByDesc('updated_at')
            ->limit(25)
            ->get(['id', 'name', 'type', 'last_generated_at'])
            ->map(static fn (Report $report): array => [
                'id' => $report->id,
                'name' => $report->name,
                'type' => $report->type,
                'last_generated_at' => $report->last_generated_at?->toIso8601String(),
            ])
            ->values()
            ->all();
    }
}
