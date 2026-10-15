<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PaymentWebhookSetting;
use App\Models\User;

class AdminPaymentWebhookSettingsPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPlatformSuperAdmin();
    }

    public function update(User $user, ?PaymentWebhookSetting $paymentWebhookSetting = null): bool
    {
        return $user->isPlatformSuperAdmin();
    }
}
