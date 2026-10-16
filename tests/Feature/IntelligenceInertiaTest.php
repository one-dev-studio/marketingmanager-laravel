<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Campaign;
use Tests\TestCase;

class IntelligenceInertiaTest extends TestCase
{
    public function test_sentiment_page_renders_inertia(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.intelligence.sentiment', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Intelligence/Sentiment')
                ->where('organizationId', (string) $organization->id)
                ->has('sidebar'));
    }

    public function test_predictive_page_renders_inertia_with_campaigns(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        Campaign::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Predictive Campaign',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $this->get(route('main.intelligence.predictive', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Intelligence/Predictive')
                ->has('campaigns', 1)
                ->where('campaigns.0.name', 'Predictive Campaign')
                ->has('sidebar'));
    }
}
