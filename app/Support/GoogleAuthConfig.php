<?php

declare(strict_types=1);

namespace App\Support;

final class GoogleAuthConfig
{
    /**
     * Whether Google OAuth sign-in is configured (credentials present in env).
     */
    public static function isEnabled(): bool
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirect = config('services.google.redirect');

        return is_string($clientId) && $clientId !== ''
            && is_string($clientSecret) && $clientSecret !== ''
            && is_string($redirect) && $redirect !== '';
    }
}
