<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ToolsInertiaTest extends TestCase
{
    public function test_tools_hub_renders_inertia_index(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.tools.index', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Tools/Index')
                ->where('organizationId', (string) $organization->id)
                ->has('tools', 7)
                ->where('tools.0.title', 'SEO Analysis')
                ->has('sidebar')
                ->has('appShell'));
    }

    public function test_seo_analysis_tool_renders_inertia_page(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.tools.seo-analysis', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Tools/SeoAnalysis')
                ->where('organizationId', (string) $organization->id)
                ->has('sidebar'));
    }
}
