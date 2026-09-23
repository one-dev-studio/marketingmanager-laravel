<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Services\Billing\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        private PaymentGatewayService $paymentGatewayService
    ) {}

    public function stripe(Request $request)
    {
        $secret = config('services.stripe.webhook_secret');
        if ($secret && $request->header('Stripe-Signature') === null) {
            abort(400, 'Missing Stripe signature.');
        }

        $this->paymentGatewayService->handleStripeEvent($request->all());

        return response()->json(['received' => true]);
    }

    public function paypal(Request $request)
    {
        Log::info('PayPal webhook received', ['type' => $request->input('event_type')]);
        $this->paymentGatewayService->handlePaypalEvent($request->all());

        return response()->json(['received' => true]);
    }
}
