<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies LinkedIn webhook payloads via the X-LI-Signature header (base64 HMAC-SHA256).
 */
class LinkedInWebhookSignatureVerifier
{
    /**
     * @return bool True when the raw body matches the LinkedIn signature for the client secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $clientSecret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $clientSecret === '') {
            return false;
        }

        $expected = base64_encode(hash_hmac('sha256', $payload, $clientSecret, true));

        return hash_equals($expected, $signatureHeader);
    }
}
