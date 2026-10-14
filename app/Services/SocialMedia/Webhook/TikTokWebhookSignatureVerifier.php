<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies TikTok webhook payloads via Tiktok-Signature (t=timestamp,s=hex_hmac).
 */
class TikTokWebhookSignatureVerifier
{
    private const int DEFAULT_TOLERANCE_SECONDS = 300;

    /**
     * @return bool True when the raw body matches the TikTok signature for the client secret.
     */
    public function verify(
        string $payload,
        ?string $signatureHeader,
        string $clientSecret,
        int $toleranceSeconds = self::DEFAULT_TOLERANCE_SECONDS,
    ): bool {
        if ($signatureHeader === null || $signatureHeader === '' || $clientSecret === '') {
            return false;
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $signatureHeader) as $element) {
            $parts = explode('=', trim($element), 2);
            if (count($parts) !== 2) {
                continue;
            }

            [$key, $value] = $parts;
            if ($key === 't') {
                $timestamp = (int) $value;
            } elseif ($key === 's') {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || $signatures === []) {
            return false;
        }

        if (abs(time() - $timestamp) > $toleranceSeconds) {
            return false;
        }

        $signedPayload = $timestamp.'.'.$payload;
        $expected = hash_hmac('sha256', $signedPayload, $clientSecret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }
}
