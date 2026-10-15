<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BuilderPreviewSanitizationTest extends TestCase
{
    public function test_email_and_landing_builder_panels_use_sandboxed_preview_component(): void
    {
        $panels = [
            resource_path('js/components/email-templates/EmailTemplateBuilderPanel.tsx'),
            resource_path('js/components/landing-pages/LandingPageBuilderPanel.tsx'),
        ];

        foreach ($panels as $path) {
            $contents = File::get($path);
            $this->assertStringContainsString('BuilderHtmlPreview', $contents, basename($path));
            $this->assertStringNotContainsString('dangerouslySetInnerHTML', $contents, basename($path));
        }
    }
}
