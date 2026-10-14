<?php

declare(strict_types=1);

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\Billing\PaymentGatewayService;
use App\Services\Billing\WebhookVerificationGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private PaymentGatewayService $paymentGatewayService,
        private WebhookVerificationGate $webhookVerificationGate,
    ) {}

    public function stripe(Request $request)
    {
        $payload = $this->webhookVerificationGate->assertStripeWebhook($request);

        $this->paymentGatewayService->handleStripeEvent($payload);

        return response()->json(['received' => true]);
    }

    public function paypal(Request $request)
    {
        $payload = $this->webhookVerificationGate->assertPaypalWebhook($request);

        Log::info('PayPal webhook received', ['type' => $payload['event_type'] ?? null]);
        $this->paymentGatewayService->handlePaypalEvent($payload);

        return response()->json(['received' => true]);
    }
}
