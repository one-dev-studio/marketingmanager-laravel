<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

/**
 * Verifies TikTok webhook TikTok-Signature header (t=timestamp,s=hex).
 */
class TikTokWebhookSignatureVerifier
{
    private const int DEFAULT_TOLERANCE_SECONDS = 300;

    /**
     * @return bool True when signature matches TikTok's signed payload format.
     */
    public function verify(string $payload, ?string $signatureHeader, string $clientSecret, int $toleranceSeconds = self::DEFAULT_TOLERANCE_SECONDS): bool
    {
        if ($signatureHeader === null || $signatureHeader === '' || $clientSecret === '') {
            return false;
        }

        $timestamp = null;
        $signature = null;

        foreach (explode(',', $signatureHeader) as $part) {
            $segments = explode('=', trim($part), 2);
            if (count($segments) !== 2) {
                continue;
            }

            [$key, $value] = $segments;
            if ($key === 't') {
                $timestamp = (int) $value;
            } elseif ($key === 's') {
                $signature = $value;
            }
        }

        if ($timestamp === null || $signature === null) {
            return false;
        }

        if (abs(time() - $timestamp) > $toleranceSeconds) {
            return false;
        }

        $signedPayload = $timestamp.'.'.$payload;
        $expected = hash_hmac('sha256', $signedPayload, $clientSecret);

        return hash_equals($expected, $signature);
    }
}
