<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LandingPage;
use Tests\TestCase;

class LandingPagesInertiaTest extends TestCase
{
    public function test_landing_pages_index_renders_inertia_list(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        LandingPage::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia LP',
            'slug' => 'inertia-lp-'.uniqid(),
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('main.landing-pages.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('LandingPages/Index')
            ->has('landingPages.data', 1)
            ->where('landingPages.data.0.name', 'Inertia LP')
            ->where('can.create', true)
            ->has('sidebar'));
    }

    public function test_landing_pages_create_renders_inertia_form(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.landing-pages.create', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('LandingPages/Create')->has('sidebar'));
    }

    public function test_landing_page_builder_renders_inertia_with_page_props(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $landingPage = LandingPage::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Builder Page',
            'slug' => 'builder-lp-'.uniqid(),
            'status' => 'draft',
            'page_data' => ['blocks' => [['type' => 'hero', 'value' => 'Hello']]],
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('main.landing-pages.builder', [
            'organizationId' => $organization->id,
            'landingPage' => $landingPage->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('LandingPages/Builder')
            ->where('page.name', 'Builder Page')
            ->has('page.page_data.blocks', 1)
            ->has('sidebar'));
    }
}
