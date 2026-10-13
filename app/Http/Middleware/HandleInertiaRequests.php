<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Agency\ClientPortalBrandingSharing;
use App\Services\Navigation\AgencyShellContextBuilder;
use App\Services\Navigation\AgencySidebarMenuBuilder;
use App\Services\Navigation\AppShellContextBuilder;
use App\Services\Navigation\SidebarMenuBuilder;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'locale' => app()->getLocale(),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'status' => fn () => $request->session()->get('status'),
                'info' => fn () => $request->session()->get('info'),
                'recoveryCodes' => fn () => $request->session()->get('recoveryCodes'),
            ],
            'ziggy' => fn () => array_merge((new Ziggy)->toArray(), [
                'location' => $request->url(),
            ]),
            'sidebar' => fn () => app(SidebarMenuBuilder::class)->buildForRequest($request),
            'appShell' => fn () => app(AppShellContextBuilder::class)->buildForRequest($request),
            'agencySidebar' => fn () => app(AgencySidebarMenuBuilder::class)->buildForRequest($request),
            'agencyShell' => fn () => app(AgencyShellContextBuilder::class)->buildForRequest($request),
            'clientPortalBranding' => fn () => app(ClientPortalBrandingSharing::class)->forRequest($request),
        ];
    }
}
