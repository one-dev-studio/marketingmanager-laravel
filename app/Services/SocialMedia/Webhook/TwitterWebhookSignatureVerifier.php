<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies Twitter / X Account Activity webhook payloads via x-twitter-webhooks-signature.
 */
class TwitterWebhookSignatureVerifier
{
    /**
     * @return bool True when the raw body matches the Twitter signature for the consumer secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $consumerSecret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $consumerSecret === '') {
            return false;
        }

        $digest = base64_encode(hash_hmac('sha256', $payload, $consumerSecret, true));
        $expected = 'sha256='.$digest;

        if (hash_equals($expected, $signatureHeader)) {
            return true;
        }

        return hash_equals($digest, $signatureHeader);
    }
}
