<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicHomePageTest extends TestCase
{
    public function test_home_page_uses_redesigned_marketing_manager_copy(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Marketing Manager', false);
        $response->assertSee('Plan campaigns.', false);
        $response->assertSee('Everything in one place', false);
        $response->assertDontSee('MarketPulse', false);
        $response->assertDontSee('10k+', false);
        $response->assertDontSee('What Our Customers Say', false);
    }
}
