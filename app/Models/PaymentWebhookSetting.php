<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentWebhookSetting extends Model
{
    protected $fillable = [
        'stripe_webhook_secret',
        'paypal_webhook_id',
        'paypal_client_id',
        'paypal_secret',
        'paypal_mode',
    ];

    protected function casts(): array
    {
        return [
            'stripe_webhook_secret' => 'encrypted',
            'paypal_webhook_id' => 'encrypted',
            'paypal_client_id' => 'encrypted',
            'paypal_secret' => 'encrypted',
        ];
    }
}
