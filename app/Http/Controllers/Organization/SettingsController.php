<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\UpdateSettingsRequest;
use App\Services\Organization\OrganizationSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function __construct(
        private OrganizationSettingsService $settingsService
    ) {}

    public function index(Request $request, string $organizationId): Response
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $settings = $this->settingsService->getAllSettings($organization);

        return Inertia::render('Organization/Settings', [
            'organizationId' => $organizationId,
            'organization' => [
                'name' => $organization->name,
                'timezone' => $organization->timezone,
                'locale' => $organization->locale,
                'settings' => [
                    'slack_webhook' => (string) ($settings['slack_webhook'] ?? ''),
                    'notify_email' => ! empty($settings['notify_email']),
                    'notify_in_app' => ! empty($settings['notify_in_app']),
                    'notify_push' => ! empty($settings['notify_push']),
                    'require_2fa' => ! empty($settings['require_2fa']),
                ],
            ],
        ]);
    }

    public function update(UpdateSettingsRequest $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);

        $organization = $this->settingsService->updateGeneralSettings(
            $organization,
            $request->validated()
        );

        if ($request->has('settings')) {
            foreach ($request->input('settings', []) as $key => $value) {
                $this->settingsService->updateSetting($organization, $key, $value);
            }
        }

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully.',
                'data' => $organization->fresh(),
            ]);
        }

        return back()->with('success', 'Settings saved.');
    }

    public function getSetting(Request $request, string $organizationId, string $key)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('view', $organization);

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $key,
                'value' => $this->settingsService->getSetting($organization, $key),
            ],
        ]);
    }

    public function updateSetting(Request $request, string $organizationId, string $key)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $request->validate(['value' => ['required']]);
        $this->settingsService->updateSetting($organization, $key, $request->input('value'));

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully.',
        ]);
    }

    public function deleteSetting(Request $request, string $organizationId, string $key)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);
        $this->settingsService->deleteSetting($organization, $key);

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully.',
        ]);
    }
}
