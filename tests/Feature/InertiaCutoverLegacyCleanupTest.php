<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InertiaCutoverLegacyCleanupTest extends TestCase
{
    public function test_customer_app_no_longer_uses_legacy_layouts_app_blade(): void
    {
        $viewsRoot = resource_path('views');

        $this->assertFileDoesNotExist($viewsRoot.'/layouts/app.blade.php');

        $bladeFiles = File::allFiles($viewsRoot);
        foreach ($bladeFiles as $file) {
            $contents = File::get($file->getPathname());
            $this->assertStringNotContainsString(
                "@extends('layouts.app')",
                $contents,
                "Legacy layouts.app reference in {$file->getRelativePathname()}"
            );
        }
    }

    public function test_legacy_vue_builder_entries_are_not_vite_inputs(): void
    {
        $viteConfig = File::get(base_path('vite.config.js'));

        $this->assertStringNotContainsString('LandingPageBuilder.vue', $viteConfig);
        $this->assertStringNotContainsString('EmailTemplateBuilder.vue', $viteConfig);
        $this->assertStringNotContainsString('resources/js/app.js', $viteConfig);
    }

    public function test_email_template_builder_route_renders_inertia(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $template = EmailTemplate::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Cutover Template',
            'subject' => 'Subject',
            'html_content' => '<p>Body</p>',
            'created_by' => $user->id,
        ]);

        $this->get(route('main.email-marketing.templates.builder', [
            'organizationId' => $organization->id,
            'emailTemplate' => $template->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('EmailTemplates/Builder'));
    }
}
