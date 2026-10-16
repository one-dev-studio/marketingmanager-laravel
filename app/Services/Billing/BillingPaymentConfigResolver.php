<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Models\PaymentWebhookSetting;
use Illuminate\Support\Facades\Cache;

/**
 * Resolves payment webhook and PayPal billing configuration: database overrides first, then config / .env.
 */
class BillingPaymentConfigResolver
{
    private const CACHE_KEY = 'payment_webhook_settings_row';

    private const CACHE_TTL_SECONDS = 60;

    public function stripeWebhookSecret(): string
    {
        $fromDb = $this->settingsRow()?->stripe_webhook_secret;

        if (is_string($fromDb) && $fromDb !== '') {
            return $fromDb;
        }

        return (string) config('services.stripe.webhook_secret');
    }

    public function paypalClientId(): string
    {
        $fromDb = $this->settingsRow()?->paypal_client_id;

        if (is_string($fromDb) && $fromDb !== '') {
            return $fromDb;
        }

        return (string) config('services.paypal.client_id');
    }

    public function paypalSecret(): string
    {
        $fromDb = $this->settingsRow()?->paypal_secret;

        if (is_string($fromDb) && $fromDb !== '') {
            return $fromDb;
        }

        return (string) config('services.paypal.secret');
    }

    public function paypalWebhookId(): string
    {
        $fromDb = $this->settingsRow()?->paypal_webhook_id;

        if (is_string($fromDb) && $fromDb !== '') {
            return $fromDb;
        }

        return (string) config('services.paypal.webhook_id');
    }

    public function paypalMode(): string
    {
        $fromDb = $this->settingsRow()?->paypal_mode;

        if (is_string($fromDb) && $fromDb !== '') {
            return $fromDb;
        }

        return (string) config('services.paypal.mode', 'sandbox');
    }

    public function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function settingsRow(): ?PaymentWebhookSetting
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL_SECONDS,
            fn () => PaymentWebhookSetting::query()->first()
        );
    }
}
