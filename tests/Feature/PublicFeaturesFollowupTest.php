<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicFeaturesFollowupTest extends TestCase
{
    public function test_features_page_uses_distinct_zigzag_screenshot_paths(): void
    {
        $response = $this->get(route('features'));

        $response->assertOk();
        $html = $response->getContent();
        $this->assertIsString($html);

        $this->assertStringContainsString('images/marketing/hero-dashboard.png', $html);
        $this->assertStringContainsString('images/marketing/product-workspace.png', $html);
        $this->assertStringContainsString('images/marketing/product-insights.png', $html);

        $this->assertSame(1, substr_count($html, 'images/marketing/hero-dashboard.png'));
    }

    public function test_features_integrations_tiles_show_labels(): void
    {
        $this->get(route('features'))
            ->assertOk()
            ->assertSee('Email', false)
            ->assertSee('Analytics', false)
            ->assertSee('features-integration-tile', false);
    }

    public function test_product_insights_marketing_image_exists(): void
    {
        $path = public_path('images/marketing/product-insights.png');
        $this->assertFileExists($path);
        $this->assertGreaterThan(10_000, filesize($path));
    }
}
