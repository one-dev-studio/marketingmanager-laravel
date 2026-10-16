<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\UpdateAgencyBrandingRequest;
use App\Http\Requests\Agency\UpdateAgencyDefaultsRequest;
use App\Http\Requests\Agency\UpdateAgencyIntegrationsRequest;
use App\Http\Requests\Agency\UpdateAgencyNotificationsRequest;
use App\Http\Requests\Agency\UpdateAgencyProfileRequest;
use App\Models\Agency;
use App\Services\AgencySettingsService;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Agency Settings Controller
 * Handles agency-wide settings configuration
 * Requires agency admin access
 */
class SettingsController extends Controller
{
    public function __construct(
        private AgencySettingsService $settingsService
    ) {}

    /**
     * Display agency settings
     */
    public function index(Request $request, Agency $agency): Response
    {
        $settings = $this->settingsService->getSettings($agency);
        $branding = $settings['branding'] ?? [];

        return Inertia::render('Agency/Settings/Index', [
            'agencyId' => (string) $agency->id,
            'agencyName' => $agency->name,
            'branding' => [
                'logo' => $branding['logo'] ?? $agency->logo,
                'primary_color' => $branding['primary_color'] ?? '#4f46e5',
                'secondary_color' => $branding['secondary_color'] ?? '#6366f1',
                AgencyPortalBrandingKeys::ENABLED => (bool) ($branding[AgencyPortalBrandingKeys::ENABLED] ?? false),
                AgencyPortalBrandingKeys::TITLE => $branding[AgencyPortalBrandingKeys::TITLE] ?? '',
                AgencyPortalBrandingKeys::WELCOME_TEXT => $branding[AgencyPortalBrandingKeys::WELCOME_TEXT] ?? '',
                AgencyPortalBrandingKeys::LOGO_URL => $branding[AgencyPortalBrandingKeys::LOGO_URL] ?? '',
                AgencyPortalBrandingKeys::PRIMARY_COLOR => $branding[AgencyPortalBrandingKeys::PRIMARY_COLOR] ?? '#4f46e5',
                AgencyPortalBrandingKeys::SECONDARY_COLOR => $branding[AgencyPortalBrandingKeys::SECONDARY_COLOR] ?? '#6366f1',
            ],
            'defaults' => [
                'timezone' => $settings['defaults']['timezone'] ?? 'UTC',
            ],
            'integrations' => [
                'slack_webhook' => $settings['integrations']['slack_webhook'] ?? '',
            ],
        ]);
    }

    /**
     * Update agency profile
     */
    public function updateProfile(UpdateAgencyProfileRequest $request, Agency $agency): RedirectResponse
    {
        $agency = $this->settingsService->updateProfile($agency, $request->validated());

        return redirect()->route('agency.settings.index', ['agency' => $agency])
            ->with('success', 'Agency profile updated successfully.');
    }

    /**
     * Update agency branding
     */
    public function updateBranding(UpdateAgencyBrandingRequest $request, Agency $agency): RedirectResponse
    {
        $agency = $this->settingsService->updateBranding($agency, $request->validated());

        return redirect()->route('agency.settings.index', ['agency' => $agency])
            ->with('success', 'Agency branding updated successfully.');
    }

    /**
     * Update default settings
     */
    public function updateDefaults(UpdateAgencyDefaultsRequest $request, Agency $agency): RedirectResponse
    {
        $agency = $this->settingsService->updateDefaults($agency, $request->validated());

        return redirect()->route('agency.settings.index', ['agency' => $agency])
            ->with('success', 'Default settings updated successfully.');
    }

    /**
     * Update integration settings
     */
    public function updateIntegrations(UpdateAgencyIntegrationsRequest $request, Agency $agency): RedirectResponse
    {
        $agency = $this->settingsService->updateIntegrations($agency, $request->validated());

        return redirect()->route('agency.settings.index', ['agency' => $agency])
            ->with('success', 'Integration settings updated successfully.');
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(UpdateAgencyNotificationsRequest $request, Agency $agency): RedirectResponse
    {
        $agency = $this->settingsService->updateNotificationPreferences($agency, $request->validated());

        return redirect()->route('agency.settings.index', ['agency' => $agency])
            ->with('success', 'Notification preferences updated successfully.');
    }
}
