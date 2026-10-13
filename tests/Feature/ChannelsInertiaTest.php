<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Channel;
use Tests\TestCase;

class ChannelsInertiaTest extends TestCase
{
    public function test_channels_index_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        Channel::factory()->create([
            'organization_id' => $organization->id,
            'display_name' => 'Inertia Channel',
        ]);

        $response = $this->get(route('main.social.channels.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Channels/Index')
            ->has('channels', 1)
            ->where('channels.0.display_name', 'Inertia Channel')
            ->has('sidebar')
            ->has('appShell')
            ->where('can.create', true));
    }

    public function test_channels_create_renders_form_page(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.social.channels.create', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Channels/Create'));
    }
}
