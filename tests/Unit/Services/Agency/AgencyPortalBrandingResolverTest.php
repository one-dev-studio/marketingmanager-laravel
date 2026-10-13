<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Agency;

use App\Models\Agency;
use App\Services\Agency\AgencyPortalBrandingResolver;
use App\Support\Agency\AgencyPortalBrandingKeys;
use Tests\TestCase;

class AgencyPortalBrandingResolverTest extends TestCase
{
    public function test_resolve_applies_defaults_for_missing_colors(): void
    {
        $agency = new Agency([
            'name' => 'Test Agency',
            'settings' => [
                'branding' => [
                    AgencyPortalBrandingKeys::ENABLED => true,
                    AgencyPortalBrandingKeys::TITLE => 'Portal',
                ],
            ],
        ]);

        $resolved = (new AgencyPortalBrandingResolver)->resolve($agency);

        $this->assertTrue($resolved['enabled']);
        $this->assertSame('Portal', $resolved['title']);
        $this->assertSame('#4f46e5', $resolved['primary_color']);
        $this->assertArrayHasKey('--client-portal-primary', $resolved['css_variables']);
    }
}
