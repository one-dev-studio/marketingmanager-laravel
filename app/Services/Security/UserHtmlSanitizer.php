<?php

declare(strict_types=1);

namespace App\Services\Security;

use HTMLPurifier;
use HTMLPurifier_Config;

/**
 * Sanitizes untrusted HTML from org users before storage or public render.
 */
class UserHtmlSanitizer
{
    private ?HTMLPurifier $purifier = null;

    /**
     * Return safe HTML for landing pages, email templates, and similar rich content.
     */
    public function sanitize(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        return $this->purifier()->purify($html);
    }

    private function purifier(): HTMLPurifier
    {
        if ($this->purifier !== null) {
            return $this->purifier;
        }

        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^https://%');

        if (app()->environment('testing')) {
            $config->set('Cache.DefinitionImpl', null);
        } else {
            $cachePath = storage_path('framework/cache/htmlpurifier');
            if (! is_dir($cachePath)) {
                mkdir($cachePath, 0755, true);
            }
            $config->set('Cache.SerializerPath', $cachePath);
        }

        $this->purifier = new HTMLPurifier($config);

        return $this->purifier;
    }
}
