<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\UpdateSettingsRequest;
use App\Services\Organization\OrganizationSettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(
        private OrganizationSettingsService $settingsService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $settings = $this->settingsService->getAllSettings($organization);

        return view('organization.settings.index', [
            'title' => 'Organization Settings',
            'organizationId' => $organizationId,
            'organization' => $organization,
            'settings' => $settings,
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
