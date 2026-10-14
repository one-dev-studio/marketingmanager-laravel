<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Contest;
use Tests\TestCase;

class Phase8AnalyticsFilesInertiaTest extends TestCase
{
    private function brandQuery(string $organizationId, Brand $brand): array
    {
        return [
            'organizationId' => $organizationId,
            'brandId' => $brand->id,
        ];
    }

    public function test_analytics_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();
        $brand = Brand::factory()->create(['organization_id' => $organization->id]);

        $this->get(route('main.analytics.index', $this->brandQuery((string) $organization->id, $brand)))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Analytics/Index')
                ->where('organizationId', (string) $organization->id)
                ->has('campaigns')
                ->has('reports')
                ->has('sidebar'));
    }

    public function test_files_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();
        $brand = Brand::factory()->create(['organization_id' => $organization->id]);

        $this->get(route('main.files.index', $this->brandQuery((string) $organization->id, $brand)))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Files/Index')
                ->where('brandName', $brand->name)
                ->has('files')
                ->has('sidebar'));
    }

    public function test_brand_assets_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();
        $brand = Brand::factory()->create(['organization_id' => $organization->id]);

        $this->get(route('main.brand-assets', $this->brandQuery((string) $organization->id, $brand)))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('BrandAssets/Index')
                ->where('brandName', $brand->name)
                ->has('guidelines')
                ->has('assetsGrouped')
                ->has('sidebar'));
    }

    public function test_automations_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.automations', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Organization/Automations/Index')
                ->has('rules')
                ->has('sidebar'));
    }

    public function test_competitions_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();
        Contest::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Summer draw',
            'status' => 'open',
        ]);

        $this->get(route('main.competitions.index', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Competitions/Index')
                ->has('contests')
                ->has('sidebar'));
    }

    public function test_competitions_create_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.competitions.create', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Competitions/Create')
                ->has('campaigns')
                ->has('sidebar'));
    }
}
