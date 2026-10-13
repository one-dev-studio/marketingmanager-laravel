<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class InertiaDemoTest extends TestCase
{
    public function test_inertia_demo_page_renders_expected_component(): void
    {
        $response = $this->get(route('inertia.demo'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Demo/Index')
            ->has('appName')
            ->has('laravelVersion')
            ->has('healthRoute'));
    }
}
