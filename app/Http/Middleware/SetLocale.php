<?php

namespace App\Http\Middleware;

use App\Models\Organization;
use App\Services\Localization\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to detect and set the application locale based on user preferences
 */
class SetLocale
{
    protected LocaleService $localeService;

    public function __construct(LocaleService $localeService)
    {
        $this->localeService = $localeService;
    }

    /**
     * Handle an incoming request and set the appropriate locale
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $organization = $this->getOrganizationFromRequest($request);

        $this->localeService->detectAndSetLocale($user, $organization);

        return $next($request);
    }

    /**
     * Get the organization from the current request context
     */
    protected function getOrganizationFromRequest(Request $request): ?Organization
    {
        $organization = $request->route('organization');
        if ($organization instanceof Organization) {
            return $organization;
        }

        $organizationId = $request->route('organizationId');
        if ($organizationId !== null && $organizationId !== '') {
            return Organization::find((int) $organizationId);
        }

        if (is_numeric($organization)) {
            return Organization::find((int) $organization);
        }

        if ($user = $request->user()) {
            return $user->primaryOrganization();
        }

        return null;
    }
}
