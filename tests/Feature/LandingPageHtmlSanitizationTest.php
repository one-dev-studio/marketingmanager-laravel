<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\LandingPage;
use Tests\TestCase;

class LandingPageHtmlSanitizationTest extends TestCase
{
    public function test_public_landing_page_strips_script_tags_from_stored_html(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $page = LandingPage::query()->create([
            'organization_id' => $organization->id,
            'name' => 'XSS Test',
            'slug' => 'xss-test-'.uniqid(),
            'status' => 'published',
            'is_active' => true,
            'html_content' => '<p>Hello</p><script>alert(1)</script>',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('public.landing-page', ['slug' => $page->slug]));

        $response->assertOk();
        $response->assertSee('Hello', false);
        $response->assertDontSee('<script>', false);
    }
}
