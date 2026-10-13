<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use Tests\TestCase;

class CampaignsInertiaTest extends TestCase
{
    public function test_campaigns_index_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        Campaign::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia Campaign',
        ]);

        $response = $this->get(route('main.campaigns.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Campaigns/Index')
            ->has('campaigns.data', 1)
            ->where('campaigns.data.0.name', 'Inertia Campaign')
            ->has('sidebar')
            ->has('appShell')
            ->where('can.create', true));
    }

    public function test_campaign_show_renders_inertia_timeline_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $campaign = Campaign::factory()->create([
            'organization_id' => $organization->id,
            'name' => 'Timeline Campaign',
        ]);

        $response = $this->get(route('main.campaigns.show', [
            'organizationId' => $organization->id,
            'campaign' => $campaign->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Campaigns/Show')
            ->where('campaign.name', 'Timeline Campaign')
            ->has('campaign.goals')
            ->has('campaign.scheduled_posts'));
    }
}
