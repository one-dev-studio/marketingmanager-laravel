<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\CreateSubscriptionRequest;
use App\Http\Requests\Organization\UpgradeSubscriptionRequest;
use App\Models\SubscriptionPlan;
use App\Services\Billing\PaymentGatewayService;
use App\Services\Organization\BillingService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BillingController extends Controller
{
    public function __construct(
        private BillingService $billingService,
        private PaymentGatewayService $paymentGatewayService
    ) {}

    public function index(Request $request, string $organizationId): Response
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $subscription = $this->billingService->getCurrentSubscription($organization)?->load('plan');
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        $invoices = $this->billingService->getInvoices($organization, 10);
        $usageStats = $this->billingService->getUsageStats($organization, $request->get('period', 'month'));
        $alerts = $this->paymentGatewayService->billingAlerts($organization, $subscription, $usageStats);

        return Inertia::render('Organization/Billing/Index', [
            'organizationId' => $organizationId,
            'subscription' => $subscription ? [
                'status' => $subscription->status,
                'trial_ends_at' => $subscription->trial_ends_at?->toDateString(),
                'plan' => $subscription->plan ? [
                    'id' => $subscription->plan->id,
                    'name' => $subscription->plan->name,
                ] : null,
            ] : null,
            'plans' => $plans->map(static fn (SubscriptionPlan $plan): array => [
                'id' => $plan->id,
                'name' => $plan->name,
                'price' => (string) $plan->price,
                'billing_cycle' => $plan->billing_cycle,
            ])->values()->all(),
            'invoices' => $invoices->through(static fn ($invoice): array => [
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status,
                'total' => (string) $invoice->total,
                'due_date' => $invoice->due_date?->toDateString(),
            ]),
            'usageStats' => [
                'period' => (string) $usageStats['period'],
                'ai_usage' => [
                    'total_tokens' => (int) ($usageStats['ai_usage']['total_tokens'] ?? 0),
                    'total_cost' => (string) ($usageStats['ai_usage']['total_cost'] ?? '0'),
                ],
            ],
            'alerts' => array_values($alerts),
            'gateways' => $this->paymentGatewayService->availableGateways(),
        ]);
    }

    public function createSubscription(CreateSubscriptionRequest $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);

        $subscription = $this->paymentGatewayService->subscribe(
            $organization,
            (int) $request->input('plan_id'),
            $request->input('gateway', 'stripe'),
            $request->boolean('is_trial', false)
        );

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully.',
                'data' => $subscription->load('plan'),
            ], 201);
        }

        return back()->with('success', 'Subscription updated.');
    }

    public function upgradeSubscription(UpgradeSubscriptionRequest $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $subscription = $this->billingService->getCurrentSubscription($organization)?->load('plan');

        if (! $subscription) {
            if ($this->wantsJson($request)) {
                return response()->json(['success' => false, 'message' => 'No active subscription found.'], 404);
            }

            return back()->with('error', 'No active subscription found.');
        }

        $subscription = $this->paymentGatewayService->changePlan($subscription, (int) $request->input('plan_id'));

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription upgraded successfully.',
                'data' => $subscription->load('plan'),
            ]);
        }

        return back()->with('success', 'Plan changed.');
    }

    public function cancelSubscription(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);
        $subscription = $this->billingService->getCurrentSubscription($organization)?->load('plan');

        if (! $subscription) {
            if ($this->wantsJson($request)) {
                return response()->json(['success' => false, 'message' => 'No active subscription found.'], 404);
            }

            return back()->with('error', 'No active subscription found.');
        }

        $subscription = $this->paymentGatewayService->cancel(
            $subscription,
            $request->boolean('cancel_at_period_end', true)
        );

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription cancelled successfully.',
                'data' => $subscription->load('plan'),
            ]);
        }

        return back()->with('success', 'Subscription cancelled.');
    }

    public function getInvoices(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        return response()->json([
            'success' => true,
            'data' => $this->billingService->getInvoices($organization, (int) $request->get('limit', 20)),
        ]);
    }

    public function getUsageStats(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        return response()->json([
            'success' => true,
            'data' => $this->billingService->getUsageStats($organization, $request->get('period', 'month')),
        ]);
    }
}
