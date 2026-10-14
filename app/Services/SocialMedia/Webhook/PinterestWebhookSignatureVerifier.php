<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies Pinterest webhook payloads via X-Pinterest-Signature (hex HMAC-SHA256 of body).
 */
class PinterestWebhookSignatureVerifier
{
    /**
     * @return bool True when the raw body matches the Pinterest signature for the signing secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $signingSecret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $signingSecret === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $payload, $signingSecret);

        return hash_equals($expected, $signatureHeader);
    }
}
