<?php

declare(strict_types=1);

namespace App\Support\Agency;

/**
 * JSON paths under agencies.settings.branding for client-portal white-label (Track 4).
 */
final class AgencyPortalBrandingKeys
{
    public const ENABLED = 'portal_enabled';

    public const TITLE = 'portal_title';

    public const WELCOME_TEXT = 'portal_welcome_text';

    public const LOGO_URL = 'portal_logo_url';

    public const PRIMARY_COLOR = 'portal_primary_color';

    public const SECONDARY_COLOR = 'portal_secondary_color';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::ENABLED,
            self::TITLE,
            self::WELCOME_TEXT,
            self::LOGO_URL,
            self::PRIMARY_COLOR,
            self::SECONDARY_COLOR,
        ];
    }
}
