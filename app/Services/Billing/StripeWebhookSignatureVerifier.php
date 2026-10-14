<?php

declare(strict_types=1);

namespace App\Services\Billing;

/**
 * Verifies Stripe webhook signatures (v1 scheme) without requiring the Stripe SDK.
 */
class StripeWebhookSignatureVerifier
{
    private const int DEFAULT_TOLERANCE_SECONDS = 300;

    /**
     * @return bool True when the payload matches the Stripe-Signature header for the given secret.
     */
    public function verify(string $payload, ?string $signatureHeader, string $secret, int $toleranceSeconds = self::DEFAULT_TOLERANCE_SECONDS): bool
    {
        if ($signatureHeader === null || $signatureHeader === '') {
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
            } elseif ($key === 'v1') {
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
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return true;
            }
        }

        return false;
    }
}
