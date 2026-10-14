<?php

declare(strict_types=1);

namespace App\Services\Navigation;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Builds admin portal sidebar navigation for Inertia layout parity with Blade admin sidebar.
 */
class AdminSidebarMenuBuilder
{
    /**
     * @return array<string, mixed>|null
     */
    public function buildForRequest(Request $request): ?array
    {
        $user = $request->user('admin') ?? $request->user();
        if ($user === null) {
            return null;
        }

        if (! $request->routeIs('admin.*') || $request->routeIs('admin.login')) {
            return null;
        }

        return $this->build($user, $request);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user, Request $request): array
    {
        $items = [
            $this->link('Dashboard', route('admin.dashboard'), 'home', $request->routeIs('admin.dashboard')),
            $this->link(
                'Organizations',
                route('admin.organizations.index'),
                'organization',
                $request->routeIs('admin.organizations.*')
            ),
            $this->link('Users', route('admin.users.index'), 'contacts', $request->routeIs('admin.users.*')),
            $this->link('Content', route('admin.content.index'), 'files', $request->routeIs('admin.content.*')),
            $this->link('Packages', route('admin.packages.index'), 'products', $request->routeIs('admin.packages.*')),
            $this->link('Costing', route('admin.costing.index'), 'analytics', $request->routeIs('admin.costing.*')),
            $this->link('Billing', route('admin.billing.index'), 'billing', $request->routeIs('admin.billing.*')),
            $this->link('Agency Team', route('admin.team.index'), 'contacts', $request->routeIs('admin.team.*')),
            $this->link('System Logs', route('admin.logs.index'), 'files', $request->routeIs('admin.logs.*')),
            $this->link('Platform Settings', route('admin.settings.index'), 'settings', $request->routeIs('admin.settings.*')),
        ];

        return [
            'title' => 'Admin Panel',
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
