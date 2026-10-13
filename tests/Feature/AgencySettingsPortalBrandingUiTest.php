<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Tests\TestCase;

class AgencySettingsPortalBrandingUiTest extends TestCase
{
    public function test_agency_settings_branding_tab_includes_portal_white_label_fields(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $response = $this->actingAs($user)
            ->get(route('agency.settings.index', ['agency' => $agency]));

        $response->assertOk();
        $response->assertSee('Client portal white-label', false);
        $response->assertSee('name="'.AgencyPortalBrandingKeys::ENABLED.'"', false);
        $response->assertSee('name="'.AgencyPortalBrandingKeys::TITLE.'"', false);
        $response->assertSee('name="'.AgencyPortalBrandingKeys::WELCOME_TEXT.'"', false);
    }
}
