<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies Meta (Facebook / Instagram) webhook payloads via X-Hub-Signature-256 or X-Hub-Signature.
 */
class MetaHubSignatureVerifier
{
    /**
     * @return bool True when the raw body matches the Meta signature header for the given app secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $appSecret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $appSecret === '') {
            return false;
        }

        if (str_starts_with($signatureHeader, 'sha256=')) {
            $expected = 'sha256='.hash_hmac('sha256', $payload, $appSecret);

            return hash_equals($expected, $signatureHeader);
        }

        if (str_starts_with($signatureHeader, 'sha1=')) {
            $expected = 'sha1='.hash_hmac('sha1', $payload, $appSecret);

            return hash_equals($expected, $signatureHeader);
        }

        return false;
    }
}
