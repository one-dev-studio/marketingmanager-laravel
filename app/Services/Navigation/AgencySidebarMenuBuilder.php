<?php

declare(strict_types=1);

namespace App\Services\Navigation;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Builds flat agency portal sidebar navigation for Inertia layout parity with Blade agency sidebar.
 */
class AgencySidebarMenuBuilder
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

        return $this->build($user, $agency, $request);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user, Agency $agency, Request $request): array
    {
        $agencyKey = ['agency' => $agency];
        $isAgencyAdmin = $user->hasRole(['agency_admin', 'agency-admin', 'admin'], $agency) || $user->isAdmin();

        $items = [
            $this->link(
                'Clients',
                route('agency.clients.index', $agencyKey),
                'organization',
                $request->routeIs('agency.clients.*')
            ),
            $this->link(
                'Tasks',
                route('agency.tasks', $agencyKey),
                'tasks',
                $request->routeIs('agency.tasks')
            ),
            $this->link(
                'Aggregated Calendar',
                route('agency.calendar', $agencyKey),
                'calendar',
                $request->routeIs('agency.calendar')
            ),
        ];

        if ($isAgencyAdmin) {
            $items[] = $this->link(
                'Billing & Invoicing',
                route('agency.billing.index', $agencyKey),
                'billing',
                $request->routeIs('agency.billing.*')
            );
        }

        $items[] = $this->link(
            'Reporting',
            route('agency.reports.index', $agencyKey),
            'analytics',
            $request->routeIs('agency.reports.*')
        );

        if ($isAgencyAdmin) {
            $items[] = $this->link(
                'Team Management',
                route('agency.team.index', $agencyKey),
                'contacts',
                $request->routeIs('agency.team.*')
            );
            $items[] = $this->link(
                'Agency Settings',
                route('agency.settings.index', $agencyKey),
                'settings',
                $request->routeIs('agency.settings.*')
            );
        }

        return [
            'title' => 'Agency View',
            'agencyId' => (string) $agency->id,
            'agencyName' => $agency->name,
            'items' => $items,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function link(string $label, string $href, string $icon, bool $isActive): array
    {
        return [
            'type' => 'link',
            'label' => $label,
            'href' => $href,
            'icon' => $icon,
            'badge' => null,
            'badgeVariant' => 'default',
            'isActive' => $isActive,
        ];
    }
}
