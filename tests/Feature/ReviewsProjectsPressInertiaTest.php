<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ReviewsProjectsPressInertiaTest extends TestCase
{
    public function test_reviews_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.reviews.index', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Reviews/Index')
                ->where('organizationId', (string) $organization->id)
                ->has('reviews')
                ->has('aggregation')
                ->has('sources')
                ->has('sidebar'));
    }

    public function test_projects_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.projects.index', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Projects/Index')
                ->where('organizationId', (string) $organization->id)
                ->has('projects')
                ->has('templates')
                ->has('sidebar'));
    }

    public function test_press_releases_index_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.press-releases.index', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('PressReleases/Index')
                ->where('organizationId', (string) $organization->id)
                ->has('pressReleases')
                ->has('sidebar'));
    }
}
