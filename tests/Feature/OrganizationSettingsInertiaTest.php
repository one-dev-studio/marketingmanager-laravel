<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class OrganizationSettingsInertiaTest extends TestCase
{
    public function test_organization_settings_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.settings', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Organization/Settings')
            ->where('organization.name', $organization->name)
            ->has('sidebar')
            ->has('appShell'));
    }
}
