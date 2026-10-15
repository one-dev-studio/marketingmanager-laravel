<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class BroadcastingInertiaShareTest extends TestCase
{
    public function test_inertia_share_includes_realtime_config_when_pusher_disabled(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $response = $this->get(route('main.dashboard', ['organizationId' => $organization->id]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->has('realtime')
            ->where('realtime.enabled', false));
    }
}
