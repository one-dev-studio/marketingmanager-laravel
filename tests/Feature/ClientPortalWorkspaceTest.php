<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\User;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Tests\TestCase;

class ClientPortalWorkspaceTest extends TestCase
{
    public function test_client_portal_workspace_lists_campaign_summaries(): void
    {
        $user = User::where('email', 'demo@test.com')->firstOrFail();
        $organization = Organization::where('slug', 'demo-company')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $settings = $agency->settings ?? [];
        $settings['branding'] = array_merge($settings['branding'] ?? [], [
            AgencyPortalBrandingKeys::ENABLED => true,
        ]);
        $agency->update(['settings' => $settings]);

        Campaign::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Portal Visible Campaign',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get(route('client-portal.workspace', [
                'agency' => $agency->id,
                'organization' => $organization->id,
            ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Client/Portal/Workspace')
                ->where('organization.name', $organization->name)
                ->has('campaigns')
                ->where('campaigns', fn ($campaigns) => collect($campaigns)->contains(
                    'name',
                    'Portal Visible Campaign',
                )));
    }

    public function test_client_portal_workspace_denies_unlinked_organization(): void
    {
        $user = User::where('email', 'demo@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();
        $otherOrganization = Organization::factory()->create();

        $settings = $agency->settings ?? [];
        $settings['branding'] = array_merge($settings['branding'] ?? [], [
            AgencyPortalBrandingKeys::ENABLED => true,
        ]);
        $agency->update(['settings' => $settings]);

        $this->actingAs($user)
            ->get(route('client-portal.workspace', [
                'agency' => $agency->id,
                'organization' => $otherOrganization->id,
            ]))
            ->assertForbidden();
    }
}
