<?php

declare(strict_types=1);

namespace App\Services\Agency;

use App\Models\Agency;
use App\Support\Agency\AgencyPortalBrandingKeys;

/**
 * Resolves white-label branding for the agency client portal from agencies.settings.branding.
 */
final class AgencyPortalBrandingResolver
{
    private const DEFAULT_PRIMARY = '#4f46e5';

    private const DEFAULT_SECONDARY = '#6366f1';

    /**
     * @return array{
     *     enabled: bool,
     *     title: string,
     *     welcome_text: string|null,
     *     logo_url: string|null,
     *     primary_color: string,
     *     secondary_color: string,
     *     css_variables: array<string, string>
     * }
     */
    public function resolve(Agency $agency): array
    {
        $branding = $agency->settings['branding'] ?? [];

        $enabled = ! empty($branding[AgencyPortalBrandingKeys::ENABLED]);
        $primary = $this->normalizeHex(
            $branding[AgencyPortalBrandingKeys::PRIMARY_COLOR] ?? null,
            self::DEFAULT_PRIMARY,
        );
        $secondary = $this->normalizeHex(
            $branding[AgencyPortalBrandingKeys::SECONDARY_COLOR] ?? null,
            self::DEFAULT_SECONDARY,
        );

        $title = (string) ($branding[AgencyPortalBrandingKeys::TITLE] ?? $agency->name);
        $welcomeText = isset($branding[AgencyPortalBrandingKeys::WELCOME_TEXT])
            ? (string) $branding[AgencyPortalBrandingKeys::WELCOME_TEXT]
            : null;
        $logoUrl = isset($branding[AgencyPortalBrandingKeys::LOGO_URL])
            ? (string) $branding[AgencyPortalBrandingKeys::LOGO_URL]
            : null;

        return [
            'enabled' => $enabled,
            'title' => $title,
            'welcome_text' => $welcomeText,
            'logo_url' => $logoUrl,
            'primary_color' => $primary,
            'secondary_color' => $secondary,
            'css_variables' => [
                '--client-portal-primary' => $primary,
                '--client-portal-secondary' => $secondary,
                '--mm-brand-600' => $primary,
            ],
        ];
    }

    public function isPortalEnabled(Agency $agency): bool
    {
        return $this->resolve($agency)['enabled'];
    }

    private function normalizeHex(?string $value, string $fallback): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        if (preg_match('/^#[0-9A-Fa-f]{6}$/', $value) === 1) {
            return $value;
        }

        return $fallback;
    }
}
