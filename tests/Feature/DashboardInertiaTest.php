<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class DashboardInertiaTest extends TestCase
{
    public function test_dashboard_renders_inertia_with_shell_props(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.dashboard', ['organizationId' => $organization->id]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Index')
            ->where('organizationId', (string) $organization->id)
            ->where('userName', $user->name)
            ->has('sidebar')
            ->has('appShell'));
    }
}
