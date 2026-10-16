<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Tests\TestCase;

class AgencyPortalBrandingTest extends TestCase
{
    public function test_agency_admin_can_update_portal_branding_settings(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->put(route('agency.settings.update-branding', ['agency' => $agency]), [
                AgencyPortalBrandingKeys::ENABLED => true,
                AgencyPortalBrandingKeys::TITLE => 'Client Hub',
                AgencyPortalBrandingKeys::WELCOME_TEXT => 'Welcome to your campaign portal.',
                AgencyPortalBrandingKeys::LOGO_URL => 'https://example.com/logo.png',
                AgencyPortalBrandingKeys::PRIMARY_COLOR => '#112233',
                AgencyPortalBrandingKeys::SECONDARY_COLOR => '#445566',
            ])
            ->assertRedirect(route('agency.settings.index', ['agency' => $agency]));

        $agency->refresh();
        $branding = $agency->settings['branding'] ?? [];

        $this->assertTrue($branding[AgencyPortalBrandingKeys::ENABLED]);
        $this->assertSame('Client Hub', $branding[AgencyPortalBrandingKeys::TITLE]);
        $this->assertSame('Welcome to your campaign portal.', $branding[AgencyPortalBrandingKeys::WELCOME_TEXT]);
        $this->assertSame('https://example.com/logo.png', $branding[AgencyPortalBrandingKeys::LOGO_URL]);
        $this->assertSame('#112233', $branding[AgencyPortalBrandingKeys::PRIMARY_COLOR]);
        $this->assertSame('#445566', $branding[AgencyPortalBrandingKeys::SECONDARY_COLOR]);
    }

    public function test_portal_branding_rejects_invalid_hex_color(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->from(route('agency.settings.index', ['agency' => $agency]))
            ->put(route('agency.settings.update-branding', ['agency' => $agency]), [
                AgencyPortalBrandingKeys::PRIMARY_COLOR => 'not-a-color',
            ])
            ->assertSessionHasErrors(AgencyPortalBrandingKeys::PRIMARY_COLOR);
    }
}
