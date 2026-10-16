<?php

declare(strict_types=1);

namespace App\Services\Navigation;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Shared header context for agency portal Inertia pages.
 */
class AgencyShellContextBuilder
{
    /**
     * @return array<string, mixed>|null
     */
    public function buildForRequest(Request $request): ?array
    {
        $user = $request->user();
        if ($user === null) {
            return null;
        }

        $agency = $request->route('agency');
        if (! $agency instanceof Agency) {
            return null;
        }

        if (! $request->routeIs('agency.*')) {
            return null;
        }

        return $this->build($user, $agency);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user, Agency $agency): array
    {
        $isAgencyAdmin = $user->hasRole(['agency_admin', 'agency-admin', 'admin'], $agency) || $user->isAdmin();

        return [
            'agency' => [
                'id' => (string) $agency->id,
                'name' => $agency->name,
            ],
            'isAgencyAdmin' => $isAgencyAdmin,
            'exitAgencyHref' => route('main.organizations'),
            'profileHref' => route('profile.show'),
            'userName' => $user->name,
            'userEmail' => $user->email,
        ];
    }
}
