<?php

namespace App\Services\Billing;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\UsageLimit;
use App\Services\Organization\BillingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    public function __construct(
        private BillingService $billingService
    ) {}

    public function availableGateways(): array
    {
        return [
            'stripe' => (bool) config('services.stripe.secret'),
            'paypal' => (bool) config('services.paypal.client_id'),
        ];
    }

    public function subscribe(Organization $organization, int $planId, string $gateway = 'stripe', bool $isTrial = false): Subscription
    {
        $subscription = $this->billingService->getCurrentSubscription($organization)
            ?? $this->billingService->createSubscription($organization, $planId, $isTrial);

        if ($subscription->plan_id !== $planId) {
            $subscription = $this->billingService->upgradeSubscription($subscription, $planId);
        }

        $plan = SubscriptionPlan::findOrFail($planId);
        $remoteId = $this->createRemoteSubscription($organization, $plan, $gateway, $isTrial);

        $subscription->update([
            'status' => $isTrial ? 'trial' : 'active',
        ]);

        $organization->settings()->updateOrCreate(
            ['key' => 'billing_gateway'],
            ['value' => json_encode([
                'gateway' => $gateway,
                'remote_subscription_id' => $remoteId,
                'updated_at' => now()->toDateTimeString(),
            ])]
        );

        $this->billingService->generateInvoice($organization, $subscription, [[
            'description' => $plan->name . ' subscription',
            'quantity' => 1,
            'unit_price' => $plan->price,
            'total' => $plan->price,
        ]]);

        return $subscription->fresh('plan');
    }

    public function changePlan(Subscription $subscription, int $planId): Subscription
    {
        return $this->billingService->upgradeSubscription($subscription, $planId);
    }

    public function cancel(Subscription $subscription, bool $cancelAtPeriodEnd = true): Subscription
    {
        return $this->billingService->cancelSubscription($subscription, $cancelAtPeriodEnd);
    }

    public function billingAlerts(Organization $organization, ?Subscription $subscription, array $usageStats): array
    {
        $alerts = [];

        if (!$subscription) {
            $alerts[] = ['type' => 'warning', 'message' => 'No active subscription. Choose a plan to keep using paid features.'];
        } elseif ($subscription->status === 'trial' && $subscription->trial_ends_at) {
            $alerts[] = ['type' => 'info', 'message' => 'Trial ends ' . $subscription->trial_ends_at->diffForHumans() . '.'];
        } elseif ($subscription->cancel_at_period_end) {
            $alerts[] = ['type' => 'warning', 'message' => 'Subscription will cancel at period end.'];
        }

        $failed = Invoice::where('organization_id', $organization->id)->where('status', 'failed')->exists();
        if ($failed) {
            $alerts[] = ['type' => 'danger', 'message' => 'A payment failed. Update your payment method.'];
        }

        $planId = $subscription?->plan_id;
        if ($planId) {
            $limits = UsageLimit::where('subscription_plan_id', $planId)->get();
            foreach ($limits as $limit) {
                if ($limit->is_unlimited) {
                    continue;
                }
                $used = (float) data_get($usageStats, "ai_usage.total_tokens", 0);
                if ($limit->feature === 'ai_tokens' && $used >= (float) $limit->limit_value) {
                    $alerts[] = ['type' => 'warning', 'message' => 'AI token usage is at or over the plan limit.'];
                }
            }
        }

        return $alerts;
    }

    public function handleStripeEvent(array $payload): void
    {
        $type = $payload['type'] ?? '';
        $object = $payload['data']['object'] ?? [];
        $this->applyWebhookStatus($type, $object['id'] ?? null, $object);
    }

    public function handlePaypalEvent(array $payload): void
    {
        $type = $payload['event_type'] ?? '';
        $resource = $payload['resource'] ?? [];
        $this->applyWebhookStatus($type, $resource['id'] ?? null, $resource);
    }

    private function applyWebhookStatus(string $type, ?string $remoteId, array $object): void
    {
        $invoiceNumber = $object['metadata']['invoice_number'] ?? null;
        $invoice = $invoiceNumber
            ? Invoice::where('invoice_number', $invoiceNumber)->first()
            : null;

        if (!$invoice) {
            return;
        }

        $paidTypes = ['invoice.paid', 'checkout.session.completed', 'PAYMENT.CAPTURE.COMPLETED'];
        $failedTypes = ['invoice.payment_failed', 'PAYMENT.CAPTURE.DENIED'];

        if (in_array($type, $paidTypes, true)) {
            $invoice->update(['status' => 'paid', 'paid_at' => now()]);
            Payment::create([
                'organization_id' => $invoice->organization_id,
                'invoice_id' => $invoice->id,
                'payment_method' => str_contains($type, 'PAYMENT') ? 'paypal' : 'stripe',
                'transaction_id' => $remoteId ?? Str::uuid()->toString(),
                'amount' => $invoice->total,
                'currency' => $invoice->currency ?? 'USD',
                'status' => 'completed',
                'processed_at' => now(),
            ]);
            \App\Models\ActivityLog::log('invoice.paid', $invoice, auth()->user(), ['status' => 'paid']);
        } elseif (in_array($type, $failedTypes, true)) {
            $invoice->update(['status' => 'failed']);
            \App\Models\ActivityLog::log('invoice.payment_failed', $invoice, auth()->user(), ['status' => 'failed']);
        }
    }

    private function createRemoteSubscription(Organization $organization, SubscriptionPlan $plan, string $gateway, bool $isTrial): ?string
    {
        if ($gateway === 'stripe' && config('services.stripe.secret')) {
            try {
                $response = Http::withToken(config('services.stripe.secret'))
                    ->asForm()
                    ->post('https://api.stripe.com/v1/checkout/sessions', [
                        'mode' => 'subscription',
                        'success_url' => url('/main/' . $organization->id . '/billing'),
                        'cancel_url' => url('/main/' . $organization->id . '/billing'),
                        'metadata[organization_id]' => $organization->id,
                        'line_items[0][quantity]' => 1,
                        'line_items[0][price_data][currency]' => 'usd',
                        'line_items[0][price_data][unit_amount]' => (int) ($plan->price * 100),
                        'line_items[0][price_data][product_data][name]' => $plan->name,
                        'line_items[0][price_data][recurring][interval]' => $plan->billing_cycle === 'yearly' ? 'year' : 'month',
                    ]);

                return $response->json('id');
            } catch (\Throwable $e) {
                Log::warning('Stripe subscribe failed; stored locally.', ['error' => $e->getMessage()]);
            }
        }

        if ($gateway === 'paypal' && config('services.paypal.client_id')) {
            return 'paypal-local-' . Str::uuid();
        }

        return 'local-' . Str::uuid();
    }
}
