<?php

declare(strict_types=1);

namespace App\Services\Billing;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Centralizes when billing webhooks must be cryptographically verified.
 */
class WebhookVerificationGate
{
    public function __construct(
        private readonly StripeWebhookSignatureVerifier $stripeVerifier,
        private readonly PayPalWebhookSignatureVerifier $paypalVerifier,
    ) {}

    /**
     * @return array<string, mixed> Decoded JSON payload after Stripe verification.
     */
    public function assertStripeWebhook(Request $request): array
    {
        $secret = (string) config('services.stripe.webhook_secret');
        $payload = $request->getContent();

        if ($secret !== '') {
            $signature = $request->header('Stripe-Signature');
            if (! $this->stripeVerifier->verify($payload, $signature, $secret)) {
                throw new BadRequestHttpException('Invalid Stripe webhook signature.');
            }
        } elseif (! app()->environment(['local', 'testing'])) {
            throw new AccessDeniedHttpException('Stripe webhook secret is not configured.');
        }

        $decoded = json_decode($payload, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array<string, mixed> Decoded JSON payload after PayPal verification.
     */
    public function assertPaypalWebhook(Request $request): array
    {
        $clientId = (string) config('services.paypal.client_id');
        $clientSecret = (string) config('services.paypal.secret');
        $webhookId = (string) config('services.paypal.webhook_id');
        $mode = (string) config('services.paypal.mode', 'sandbox');

        $decoded = $request->json()->all();
        if (! is_array($decoded)) {
            $decoded = [];
        }

        $verificationConfigured = $clientId !== '' && $clientSecret !== '' && $webhookId !== '';

        if ($verificationConfigured) {
            if (! $this->paypalVerifier->verify($request, $decoded, $clientId, $clientSecret, $webhookId, $mode)) {
                throw new BadRequestHttpException('Invalid PayPal webhook signature.');
            }
        } elseif (! app()->environment(['local', 'testing'])) {
            throw new AccessDeniedHttpException('PayPal webhook verification is not configured.');
        }

        return $decoded;
    }
}
