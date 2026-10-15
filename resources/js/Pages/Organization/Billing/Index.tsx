import AppLayout from '@/Layouts/AppLayout';
import type {
    BillingAlert,
    BillingInvoiceRow,
    BillingPaymentMethodSummary,
    BillingPaymentRow,
    BillingPlanOption,
    BillingSubscription,
} from '@/types/organization-billing';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedInvoices {
    data: BillingInvoiceRow[];
    links: PaginationLink[];
}

interface PaginatedPayments {
    data: BillingPaymentRow[];
    links: PaginationLink[];
}

export interface OrganizationBillingIndexProps {
    organizationId: string;
    subscription: BillingSubscription | null;
    plans: BillingPlanOption[];
    invoices: PaginatedInvoices;
    payments: PaginatedPayments;
    paymentMethod: BillingPaymentMethodSummary | null;
    usageStats: {
        period: string;
        ai_usage: { total_tokens: number; total_cost: string };
    };
    alerts: BillingAlert[];
    gateways: { stripe: boolean; paypal: boolean };
}

function alertClasses(type: string): string {
    if (type === 'danger') {
        return 'bg-red-50 text-red-800';
    }
    if (type === 'warning') {
        return 'bg-yellow-50 text-yellow-800';
    }
    return 'bg-blue-50 text-blue-800';
}

export default function Index({
    organizationId,
    subscription,
    plans,
    invoices,
    payments,
    paymentMethod,
    usageStats,
    alerts,
    gateways,
}: OrganizationBillingIndexProps) {
    const changePlan = (planId: number) => {
        if (subscription) {
            router.put(
                route('main.billing.subscription.upgrade', { organizationId }),
                { plan_id: planId },
                { preserveScroll: true },
            );
            return;
        }
        router.post(
            route('main.billing.subscription.create', { organizationId }),
            { plan_id: planId, gateway: 'stripe' },
            { preserveScroll: true },
        );
    };

    const cancelSubscription = () => {
        if (!subscription) {
            return;
        }
        router.put(
            route('main.billing.subscription.cancel', { organizationId }),
            { cancel_at_period_end: true },
            { preserveScroll: true },
        );
    };

    const invoiceRows = invoices.data ?? [];
    const paymentRows = payments.data ?? [];

    return (
        <AppLayout title="Billing">
            <div className="mx-auto max-w-4xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Billing</h1>
                    <p className="mt-1 text-sm text-gray-600">Subscription, usage, and invoices</p>
                </div>

                {alerts.map((alert, index) => (
                    <div
                        key={`${alert.type}-${index}`}
                        className={`rounded-md px-4 py-3 text-sm ${alertClasses(alert.type)}`}
                    >
                        {alert.message}
                    </div>
                ))}

                <div className="rounded-lg border border-gray-200 bg-white p-6">
                    <h2 className="font-medium text-gray-900">Current plan</h2>
                    <p className="mt-1 text-sm text-gray-600">
                        {subscription?.plan?.name ?? 'None'} · {subscription?.status ?? 'inactive'}
                        {subscription?.trial_ends_at
                            ? ` · trial until ${subscription.trial_ends_at}`
                            : null}
                    </p>
                    <p className="mt-2 text-xs text-gray-400">
                        Gateways: Stripe {gateways.stripe ? 'configured' : 'needs STRIPE_SECRET'} ·
                        PayPal {gateways.paypal ? 'configured' : 'needs PAYPAL_CLIENT_ID'}
                    </p>
                    {subscription && subscription.status !== 'cancelled' ? (
                        <button
                            type="button"
                            className="mt-4 text-sm text-red-700 hover:text-red-900"
                            onClick={cancelSubscription}
                        >
                            Cancel at period end
                        </button>
                    ) : null}
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-4">
                    <h2 className="font-medium text-gray-900">Payment method</h2>
                    {paymentMethod ? (
                        <p className="mt-1 text-sm text-gray-600">
                            Billing via {paymentMethod.label} ({paymentMethod.gateway})
                        </p>
                    ) : (
                        <p className="mt-1 text-sm text-gray-600">
                            No saved gateway yet. Subscribe to a plan to set Stripe or PayPal.
                        </p>
                    )}
                </div>

                <div className="grid gap-4 md:grid-cols-3">
                    {plans.map((plan) => (
                        <div
                            key={plan.id}
                            className="rounded-lg border border-gray-200 bg-white p-4"
                        >
                            <h3 className="font-medium text-gray-900">{plan.name}</h3>
                            <p className="text-sm text-gray-600">
                                ${plan.price} / {plan.billing_cycle}
                            </p>
                            <button
                                type="button"
                                className="mt-3 text-sm text-primary-700 hover:text-primary-900"
                                onClick={() => changePlan(plan.id)}
                            >
                                {subscription ? 'Switch' : 'Subscribe'}
                            </button>
                        </div>
                    ))}
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-4">
                    <h2 className="font-medium text-gray-900">
                        Usage ({usageStats.period})
                    </h2>
                    <p className="mt-1 text-sm text-gray-600">
                        AI tokens {usageStats.ai_usage.total_tokens} · cost $
                        {usageStats.ai_usage.total_cost}
                    </p>
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <h2 className="border-b border-gray-100 p-4 font-medium text-gray-900">
                        Invoices
                    </h2>
                    {invoiceRows.length === 0 ? (
                        <p className="p-4 text-sm text-gray-600">No invoices yet.</p>
                    ) : (
                        invoiceRows.map((invoice) => (
                            <div
                                key={invoice.id}
                                className="flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 px-4 py-3 text-sm"
                            >
                                <span>
                                    {invoice.invoice_number} · {invoice.status}
                                </span>
                                <span className="flex items-center gap-3">
                                    <span>
                                        ${invoice.total} due {invoice.due_date ?? '—'}
                                    </span>
                                    <a
                                        className="text-primary-700 hover:text-primary-900"
                                        href={route('main.billing.invoices.download', {
                                            organizationId,
                                            invoice: invoice.id,
                                        })}
                                    >
                                        Download PDF
                                    </a>
                                </span>
                            </div>
                        ))
                    )}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <h2 className="border-b border-gray-100 p-4 font-medium text-gray-900">
                        Payment history
                    </h2>
                    {paymentRows.length === 0 ? (
                        <p className="p-4 text-sm text-gray-600">No payments recorded yet.</p>
                    ) : (
                        paymentRows.map((payment) => (
                            <div
                                key={payment.id}
                                className="flex flex-wrap justify-between gap-2 border-t border-gray-100 px-4 py-3 text-sm"
                            >
                                <span>
                                    {payment.invoice_number ?? 'Invoice'} · {payment.payment_method}{' '}
                                    · {payment.status}
                                </span>
                                <span>
                                    ${payment.amount}
                                    {payment.processed_at ? ` · ${payment.processed_at}` : ''}
                                </span>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
