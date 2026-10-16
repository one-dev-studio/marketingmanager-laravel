import { Button } from '@/components/ui/button';
import AdminLayout from '@/Layouts/AdminLayout';
import { useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface MaskedSecretField {
    is_set: boolean;
    last_four: string | null;
    value?: string;
}

export interface AdminPaymentWebhookSettingsProps {
    fields: {
        stripe_webhook_secret: MaskedSecretField;
        paypal_webhook_id: MaskedSecretField;
        paypal_client_id: MaskedSecretField;
        paypal_secret: MaskedSecretField;
        paypal_mode: MaskedSecretField;
    };
}

function SecretFieldHint({ field }: { field: MaskedSecretField }) {
    if (!field.is_set) {
        return <p className="text-sm text-gray-500">Not set in database (using environment fallback when present).</p>;
    }

    return (
        <p className="text-sm text-gray-600">
            Configured · ends with <span className="font-mono font-medium">{field.last_four ?? '****'}</span>
        </p>
    );
}

export default function Payments({ fields }: AdminPaymentWebhookSettingsProps) {
    const form = useForm({
        stripe_webhook_secret: '',
        paypal_webhook_id: '',
        paypal_client_id: '',
        paypal_secret: '',
        paypal_mode: fields.paypal_mode.value ?? 'sandbox',
    });

    return (
        <AdminLayout title="Payment & Webhook Settings">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Payment &amp; webhook settings</h2>
                    <p className="mt-1 text-gray-600">
                        Store Stripe and PayPal webhook credentials in the database. Values are write-only; leave a field
                        blank to keep the current secret.
                    </p>
                </div>

                <form
                    className="space-y-6"
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.put(route('admin.settings.payments.update'));
                    }}
                >
                    <div className="space-y-2 rounded-lg border border-gray-200 p-4">
                        <label className="block text-sm font-semibold text-gray-900" htmlFor="stripe_webhook_secret">
                            Stripe webhook secret
                        </label>
                        <SecretFieldHint field={fields.stripe_webhook_secret} />
                        <input
                            id="stripe_webhook_secret"
                            type="password"
                            autoComplete="off"
                            placeholder="Enter new secret to replace"
                            value={form.data.stripe_webhook_secret}
                            onChange={(event) => form.setData('stripe_webhook_secret', event.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2"
                        />
                    </div>

                    <div className="space-y-2 rounded-lg border border-gray-200 p-4">
                        <label className="block text-sm font-semibold text-gray-900" htmlFor="paypal_client_id">
                            PayPal client ID
                        </label>
                        <SecretFieldHint field={fields.paypal_client_id} />
                        <input
                            id="paypal_client_id"
                            type="password"
                            autoComplete="off"
                            placeholder="Enter new client ID to replace"
                            value={form.data.paypal_client_id}
                            onChange={(event) => form.setData('paypal_client_id', event.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2"
                        />
                    </div>

                    <div className="space-y-2 rounded-lg border border-gray-200 p-4">
                        <label className="block text-sm font-semibold text-gray-900" htmlFor="paypal_secret">
                            PayPal secret
                        </label>
                        <SecretFieldHint field={fields.paypal_secret} />
                        <input
                            id="paypal_secret"
                            type="password"
                            autoComplete="off"
                            placeholder="Enter new secret to replace"
                            value={form.data.paypal_secret}
                            onChange={(event) => form.setData('paypal_secret', event.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2"
                        />
                    </div>

                    <div className="space-y-2 rounded-lg border border-gray-200 p-4">
                        <label className="block text-sm font-semibold text-gray-900" htmlFor="paypal_webhook_id">
                            PayPal webhook ID
                        </label>
                        <SecretFieldHint field={fields.paypal_webhook_id} />
                        <input
                            id="paypal_webhook_id"
                            type="password"
                            autoComplete="off"
                            placeholder="Enter new webhook ID to replace"
                            value={form.data.paypal_webhook_id}
                            onChange={(event) => form.setData('paypal_webhook_id', event.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2"
                        />
                    </div>

                    <div className="space-y-2 rounded-lg border border-gray-200 p-4">
                        <label className="block text-sm font-semibold text-gray-900" htmlFor="paypal_mode">
                            PayPal mode
                        </label>
                        <select
                            id="paypal_mode"
                            value={form.data.paypal_mode}
                            onChange={(event) => form.setData('paypal_mode', event.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2"
                        >
                            <option value="sandbox">sandbox</option>
                            <option value="live">live</option>
                        </select>
                    </div>

                    <Button type="submit" disabled={form.processing}>Save settings</Button>
                </form>
            </div>
        </AdminLayout>
    );
}
