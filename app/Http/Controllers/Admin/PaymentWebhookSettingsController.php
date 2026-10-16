<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePaymentWebhookSettingsRequest;
use App\Models\PaymentWebhookSetting;
use App\Services\Admin\PaymentWebhookSettingsService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentWebhookSettingsController extends Controller
{
    public function __construct(
        private readonly PaymentWebhookSettingsService $paymentWebhookSettingsService,
    ) {}

    public function index(): Response
    {
        $this->authorize('viewAny', PaymentWebhookSetting::class);

        return Inertia::render('Admin/Settings/Payments', [
            'fields' => $this->paymentWebhookSettingsService->maskedFieldsForUi(),
        ]);
    }

    public function update(UpdatePaymentWebhookSettingsRequest $request): RedirectResponse
    {
        $this->authorize('update', PaymentWebhookSetting::class);

        $user = $request->user('admin') ?? $request->user();
        $adminUserId = $user !== null ? (int) $user->id : 0;

        $this->paymentWebhookSettingsService->updateFromInput(
            $request->validated(),
            $adminUserId,
        );

        return redirect()
            ->route('admin.settings.payments.index')
            ->with('success', 'Payment and webhook settings updated.');
    }
}
