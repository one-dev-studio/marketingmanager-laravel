<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class Phase8ToolsRemainingInertiaTest extends TestCase
{
    public function test_ai_blog_tool_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.tools.blog', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Tools/AiTool')
                ->where('toolKey', 'blog')
                ->has('sidebar'));
    }

    public function test_label_inspiration_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.tools.label-inspiration', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Tools/LabelInspiration')
                ->has('brands')
                ->has('products'));
    }

    public function test_competitors_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.competitors.index', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Competitors/Index')
                ->has('competitors.data'));
    }

    public function test_paid_campaigns_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.paid-campaigns.index', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('PaidAds/Campaigns')
                ->has('platforms'));
    }

    public function test_email_campaigns_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.email-marketing.campaigns.index', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('EmailMarketing/Campaigns/Index')
                ->has('campaigns.data'));
    }
}
