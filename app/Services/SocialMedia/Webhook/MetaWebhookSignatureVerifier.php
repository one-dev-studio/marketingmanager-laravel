<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies Meta (Facebook / Instagram) X-Hub-Signature-256 webhook payloads.
 */
class MetaWebhookSignatureVerifier
{
    /**
     * @return bool True when the raw body matches the X-Hub-Signature-256 header for the app secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $appSecret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $appSecret === '') {
            return false;
        }

        if (! str_starts_with($signatureHeader, 'sha256=')) {
            return false;
        }

        $expected = substr($signatureHeader, 7);
        $computed = hash_hmac('sha256', $payload, $appSecret);

        return hash_equals($computed, $expected);
    }
}
