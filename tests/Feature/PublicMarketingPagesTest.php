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
            ->assertSee('How we build', false)
            ->assertSee('What we stand for', false)
            ->assertDontSee('10k+', false)
            ->assertDontSee('John Smith', false);
    }

    public function test_pricing_compare_table_matches_legacy_plan_limits(): void
    {
        $html = $this->get(route('pricing'))->assertOk()->getContent();
        $this->assertIsString($html);

        $this->assertStringContainsString('$29', $html);
        $this->assertStringContainsString('$79', $html);
        $this->assertStringContainsString('$199', $html);
        $this->assertStringContainsString('14-day trial', $html);
        $this->assertStringContainsString('Annual billing saves 20%', $html);
        $this->assertStringNotContainsString('Team members</th>', $html);
    }
}
