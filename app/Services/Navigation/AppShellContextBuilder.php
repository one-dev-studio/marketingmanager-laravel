<?php

declare(strict_types=1);

namespace App\Services\Navigation;

use App\Models\Organization;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Http\Request;

/**
 * Builds shared header / shell context for Inertia app layout (organization routes).
 */
class AppShellContextBuilder
{
    public function __construct(
        private readonly ReviewService $reviewService,
    ) {}

    /**
     * @return array<string, mixed>|null
     */
    public function buildForRequest(Request $request): ?array
    {
        $user = $request->user();
        if ($user === null) {
            return null;
        }

        $organizationId = $request->route('organizationId');
        if ($organizationId === null) {
            return null;
        }

        $organization = Organization::find($organizationId);
        if ($organization === null) {
            return null;
        }

        return $this->build($user, $organization, $request);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user, Organization $organization, Request $request): array
    {
        $organizationId = (int) $organization->id;
        $reviewStats = $this->reviewService->getReviewStats($organizationId);
        $reviewPending = (int) ($reviewStats['pending'] ?? 0);

        $organizations = $user->organizations()
            ->orderBy('name')
            ->get(['organizations.id', 'organizations.name']);

        $locales = [];
        foreach (enabled_locales() as $code => $locale) {
            $locales[] = [
                'code' => $code,
                'name' => $locale['name'] ?? $code,
                'href' => route('locale.switch', ['locale' => $code]),
                'isCurrent' => current_locale() === $code,
            ];
        }

        return [
            'currentOrganization' => [
                'id' => (string) $organization->id,
                'name' => $organization->name,
            ],
            'organizations' => $organizations->map(static fn (Organization $org): array => [
                'id' => (string) $org->id,
                'name' => $org->name,
                'href' => route('main.dashboard', ['organizationId' => $org->id]),
            ])->values()->all(),
            'reviewPendingCount' => $reviewPending,
            'reviewHref' => route('main.content-approvals.index', ['organizationId' => $organizationId]),
            'notificationCount' => 0,
            'locales' => $locales,
            'currentLocale' => current_locale(),
            'dashboardHref' => route('main.dashboard', ['organizationId' => $organizationId]),
        ];
    }
}
