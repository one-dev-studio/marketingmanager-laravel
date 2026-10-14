<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Shared HMAC-SHA256 (hex digest) verification for providers that sign the raw body.
 */
class HmacSha256HexWebhookSignatureVerifier
{
    /**
     * @return bool True when the header value equals the hex HMAC of the payload.
     */
    public function verify(string $payload, ?string $signatureHeader, string $secret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $secret === '') {
            return false;
        }

        $computed = hash_hmac('sha256', $payload, $secret);

        return hash_equals($computed, $signatureHeader);
    }
}
