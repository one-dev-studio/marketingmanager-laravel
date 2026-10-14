<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Tests\TestCase;

class AgencySettingsPortalBrandingUiTest extends TestCase
{
    public function test_agency_settings_branding_includes_portal_white_label_props(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->get(route('agency.settings.index', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Settings/Index')
                ->has('branding.'.AgencyPortalBrandingKeys::ENABLED)
                ->has('branding.'.AgencyPortalBrandingKeys::TITLE)
                ->has('branding.'.AgencyPortalBrandingKeys::WELCOME_TEXT));
    }
}
