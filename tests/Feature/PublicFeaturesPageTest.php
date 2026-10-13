<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicFeaturesPageTest extends TestCase
{
    public function test_features_page_uses_redesigned_marketing_manager_copy(): void
    {
        $response = $this->get('/features');

        $response->assertOk();
        $response->assertSee('href="/css/public.css"', false);
        $response->assertSee('Powerful features in one workspace', false);
        $response->assertSee('features-zigzag', false);
        $response->assertSee('images/marketing/hero-dashboard.png', false);
        $response->assertDontSee('MarketPulse', false);
        $response->assertDontSee('Powerful Features for Modern Marketing', false);
    }
}
