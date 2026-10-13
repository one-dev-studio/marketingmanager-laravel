<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ViteAssetUrlTest extends TestCase
{
    public function test_login_vite_assets_use_request_host_in_non_deployed_environments(): void
    {
        $this->withVite();

        $response = $this->get('http://127.0.0.1:8000/login');

        $response->assertOk();
        $content = $response->getContent();
        $this->assertIsString($content);
        $this->assertStringContainsString('127.0.0.1:8000/build/assets/', $content);
        $this->assertStringNotContainsString('http://localhost/build/assets/', $content);
    }
}
