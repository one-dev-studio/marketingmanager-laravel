import AdminLayout from '@/Layouts/AdminLayout';

interface SubscriptionRow {
    id: number;
    status: string | null;
    organization: { name: string } | null;
    plan: { name: string } | null;
}

export interface AdminBillingIndexProps {
    subscriptions: SubscriptionRow[];
    openInvoices: number;
    paidTotal: number | string;
}

export default function Index({ subscriptions, openInvoices, paidTotal }: AdminBillingIndexProps) {
    return (
        <AdminLayout title="Platform Billing">
            <div className="space-y-6">
                <h2 className="text-2xl font-bold text-gray-900">Platform billing</h2>
                <div className="grid gap-4 md:grid-cols-3">
                    <div className="rounded-lg border p-4">
                        <p className="text-sm text-gray-500">Subscriptions</p>
                        <p className="text-2xl font-semibold">{subscriptions.length}</p>
                    </div>
                    <div className="rounded-lg border p-4">
                        <p className="text-sm text-gray-500">Open invoices</p>
                        <p className="text-2xl font-semibold">{openInvoices}</p>
                    </div>
                    <div className="rounded-lg border p-4">
                        <p className="text-sm text-gray-500">Paid (30d)</p>
                        <p className="text-2xl font-semibold">{paidTotal}</p>
                    </div>
                </div>
                <table className="min-w-full border bg-white text-sm">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="p-3 text-left">Org</th>
                            <th className="p-3 text-left">Plan</th>
                            <th className="p-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {subscriptions.map((sub) => (
                            <tr key={sub.id} className="border-t">
                                <td className="p-3">{sub.organization?.name ?? '—'}</td>
                                <td className="p-3">{sub.plan?.name ?? '—'}</td>
                                <td className="p-3">{sub.status}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </AdminLayout>
    );
}
