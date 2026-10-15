<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentWebhookSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isPlatformSuperAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'stripe_webhook_secret' => ['sometimes', 'nullable', 'string', 'max:500'],
            'paypal_webhook_id' => ['sometimes', 'nullable', 'string', 'max:500'],
            'paypal_client_id' => ['sometimes', 'nullable', 'string', 'max:500'],
            'paypal_secret' => ['sometimes', 'nullable', 'string', 'max:500'],
            'paypal_mode' => ['sometimes', 'nullable', 'string', Rule::in(['sandbox', 'live'])],
        ];
    }
}
