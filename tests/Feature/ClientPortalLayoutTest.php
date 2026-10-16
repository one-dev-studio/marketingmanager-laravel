<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Organization;
use App\Models\User;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Tests\TestCase;

class ClientPortalLayoutTest extends TestCase
{
    /**
     * @return array{0: User, 1: Organization}
     */
    private function actingAsDemoOrganizationAdmin(): array
    {
        $user = User::where('email', 'demo@test.com')->firstOrFail();
        $organization = Organization::where('slug', 'demo-company')->firstOrFail();
        $this->actingAs($user);

        return [$user, $organization];
    }

    public function test_client_portal_home_renders_branded_inertia_page(): void
    {
        [, $organization] = $this->actingAsDemoOrganizationAdmin();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $settings = $agency->settings ?? [];
        $settings['branding'] = array_merge($settings['branding'] ?? [], [
            AgencyPortalBrandingKeys::ENABLED => true,
            AgencyPortalBrandingKeys::TITLE => 'Client Hub',
            AgencyPortalBrandingKeys::WELCOME_TEXT => 'Welcome to your campaign portal.',
            AgencyPortalBrandingKeys::PRIMARY_COLOR => '#112233',
            AgencyPortalBrandingKeys::SECONDARY_COLOR => '#445566',
        ]);
        $agency->update(['settings' => $settings]);

        $response = $this->get(route('client-portal.home', ['agency' => $agency->id]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Client/Portal/Home')
            ->where('portalBranding.title', 'Client Hub')
            ->where('portalBranding.primary_color', '#112233')
            ->has('organizations', 1));
    }

    public function test_disabled_client_portal_returns_not_found(): void
    {
        $this->actingAsDemoOrganizationAdmin();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $settings = $agency->settings ?? [];
        $settings['branding'] = array_merge($settings['branding'] ?? [], [
            AgencyPortalBrandingKeys::ENABLED => false,
        ]);
        $agency->update(['settings' => $settings]);

        $this->get(route('client-portal.home', ['agency' => $agency->id]))
            ->assertNotFound();
    }

    public function test_organization_page_shares_client_portal_branding_when_enabled(): void
    {
        [, $organization] = $this->actingAsDemoOrganizationAdmin();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $settings = $agency->settings ?? [];
        $settings['branding'] = array_merge($settings['branding'] ?? [], [
            AgencyPortalBrandingKeys::ENABLED => true,
            AgencyPortalBrandingKeys::TITLE => 'Branded Portal',
        ]);
        $agency->update(['settings' => $settings]);

        $response = $this->get(route('main.settings', ['organizationId' => $organization->id]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->has('clientPortalBranding')
            ->where('clientPortalBranding.title', 'Branded Portal'));
    }
}
