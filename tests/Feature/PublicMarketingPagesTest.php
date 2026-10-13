<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PublicMarketingPagesTest extends TestCase
{
    public function test_pricing_page_renders_redesigned_sections(): void
    {
        $this->get(route('pricing'))
            ->assertOk()
            ->assertSee('Plans that scale with your team', false)
            ->assertSee('Compare at a glance', false);
    }

    public function test_about_page_renders_redesigned_sections(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Built for marketers who ship', false)
            ->assertSee('What we stand for', false);
    }
}
