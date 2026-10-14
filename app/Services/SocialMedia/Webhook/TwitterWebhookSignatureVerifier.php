<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies Twitter / X Account Activity API webhook signatures.
 */
class TwitterWebhookSignatureVerifier
{
    /**
     * @return bool True when the payload matches X-Twitter-Webhooks-Signature for the consumer secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $consumerSecret): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $consumerSecret === '') {
            return false;
        }

        $expected = base64_encode(hash_hmac('sha256', $payload, $consumerSecret, true));

        return hash_equals($expected, $signatureHeader);
    }
}
