<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\PaymentWebhookSetting;
use App\Services\Billing\BillingPaymentConfigResolver;
use Illuminate\Support\Str;

class PaymentWebhookSettingsService
{
    public const FIELD_STRIPE_WEBHOOK_SECRET = 'stripe_webhook_secret';

    public const FIELD_PAYPAL_WEBHOOK_ID = 'paypal_webhook_id';

    public const FIELD_PAYPAL_CLIENT_ID = 'paypal_client_id';

    public const FIELD_PAYPAL_SECRET = 'paypal_secret';

    public const FIELD_PAYPAL_MODE = 'paypal_mode';

    public function __construct(
        private readonly PlatformSettingsService $platformSettingsService,
        private readonly BillingPaymentConfigResolver $billingPaymentConfigResolver,
    ) {}

    /**
     * @return array<string, array{is_set: bool, last_four: string|null, value?: string}>
     */
    public function maskedFieldsForUi(): array
    {
        $row = PaymentWebhookSetting::query()->first();

        return [
            self::FIELD_STRIPE_WEBHOOK_SECRET => $this->maskSecret($row?->stripe_webhook_secret),
            self::FIELD_PAYPAL_WEBHOOK_ID => $this->maskSecret($row?->paypal_webhook_id),
            self::FIELD_PAYPAL_CLIENT_ID => $this->maskSecret($row?->paypal_client_id),
            self::FIELD_PAYPAL_SECRET => $this->maskSecret($row?->paypal_secret),
            self::FIELD_PAYPAL_MODE => [
                'is_set' => $row?->paypal_mode !== null && $row->paypal_mode !== '',
                'last_four' => null,
                'value' => $row?->paypal_mode ?? $this->billingPaymentConfigResolver->paypalMode(),
            ],
        ];
    }

    /**
     * @param  array<string, string|null>  $input
     */
    public function updateFromInput(array $input, int $adminUserId): PaymentWebhookSetting
    {
        $setting = PaymentWebhookSetting::query()->first()
            ?? new PaymentWebhookSetting;

        $secretFields = [
            self::FIELD_STRIPE_WEBHOOK_SECRET,
            self::FIELD_PAYPAL_WEBHOOK_ID,
            self::FIELD_PAYPAL_CLIENT_ID,
            self::FIELD_PAYPAL_SECRET,
        ];

        foreach ($secretFields as $field) {
            if (! array_key_exists($field, $input)) {
                continue;
            }

            $value = $input[$field];
            if ($value === null || $value === '') {
                continue;
            }

            $setting->{$field} = $value;
            $this->auditFieldChange($field, $adminUserId);
        }

        if (array_key_exists(self::FIELD_PAYPAL_MODE, $input)) {
            $mode = $input[self::FIELD_PAYPAL_MODE];
            if (is_string($mode) && $mode !== '') {
                $setting->paypal_mode = $mode;
                $this->auditFieldChange(self::FIELD_PAYPAL_MODE, $adminUserId);
            }
        }

        $setting->save();
        $this->billingPaymentConfigResolver->forgetCache();

        return $setting;
    }

    /**
     * @return array{is_set: bool, last_four: string|null}
     */
    private function maskSecret(?string $value): array
    {
        if ($value === null || $value === '') {
            return ['is_set' => false, 'last_four' => null];
        }

        $normalized = Str::of($value)->toString();

        return [
            'is_set' => true,
            'last_four' => Str::length($normalized) <= 4
                ? $normalized
                : Str::substr($normalized, -4),
        ];
    }

    private function auditFieldChange(string $fieldKey, int $adminUserId): void
    {
        $this->platformSettingsService->logSystemEvent(
            'info',
            'Payment webhook setting updated',
            ['field_key' => $fieldKey],
            $adminUserId,
        );
    }
}
