<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicMarketingImagesTest extends TestCase
{
    public function test_home_marketing_images_exist_and_are_non_empty(): void
    {
        $paths = [
            public_path('images/marketing/hero-dashboard.png'),
            public_path('images/marketing/product-workspace.png'),
        ];

        foreach ($paths as $path) {
            $this->assertFileExists($path, "Missing marketing image: {$path}");
            $this->assertGreaterThan(50_000, filesize($path), "Marketing image too small: {$path}");
        }
    }
}
