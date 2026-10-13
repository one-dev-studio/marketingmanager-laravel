<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class OrganizationAdminInertiaTest extends TestCase
{
    public function test_organization_team_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.team', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Organization/Team/Index')
            ->has('teamMembers')
            ->has('availableRoles')
            ->has('sidebar')
            ->has('appShell'));
    }

    public function test_organization_billing_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.billing', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Organization/Billing/Index')
            ->has('plans')
            ->has('invoices')
            ->has('sidebar')
            ->has('appShell'));
    }

    public function test_organization_storage_sources_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.storage-sources', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Organization/StorageSources/Index')
            ->has('providers', 3)
            ->has('quota')
            ->has('sidebar')
            ->has('appShell'));
    }
}
