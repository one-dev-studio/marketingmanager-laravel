<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class InertiaAppShellTest extends TestCase
{
    public function test_org_inertia_shell_renders_with_sidebar_props(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.inertia.shell', ['organizationId' => $organization->id]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Demo/Shell')
            ->has('sidebar')
            ->where('sidebar.organizationId', (string) $organization->id)
            ->has('sidebar.items')
            ->has('appShell')
            ->where('appShell.currentOrganization.id', (string) $organization->id)
            ->has('appShell.locales'));
    }
}
