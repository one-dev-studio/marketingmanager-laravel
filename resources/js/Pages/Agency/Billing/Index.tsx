import { Button } from '@/components/ui/button';
import AgencyLayout from '@/Layouts/AgencyLayout';
import { router } from '@inertiajs/react';

interface InvoiceItem {
    id: number;
    invoice_number: string;
    organization_name: string | null;
    total: string | number;
    status: string;
    due_date: string | null;
    download_href: string;
    can_mark_paid: boolean;
    pay_action: string;
}

interface PaginatedInvoices {
    data: InvoiceItem[];
}

export interface AgencyBillingIndexProps {
    agencyId: string;
    summary: {
        ytd?: number | string;
        total?: number | string;
        pending?: number | string;
        overdue?: number | string;
    };
    invoices: PaginatedInvoices;
    remindersAction: string;
}

export default function Index({ summary, invoices, remindersAction }: AgencyBillingIndexProps) {
    const items = invoices.data ?? [];

    return (
        <AgencyLayout title="Billing">
            <div className="space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Billing & invoicing</h1>
                <div className="grid gap-4 md:grid-cols-3">
                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <p className="text-sm text-gray-500">Total billed (YTD)</p>
                        <p className="text-2xl font-semibold text-gray-900">{summary.ytd ?? summary.total ?? 0}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <p className="text-sm text-gray-500">Pending</p>
                        <p className="text-2xl font-semibold text-gray-900">{summary.pending ?? 0}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <p className="text-sm text-gray-500">Overdue</p>
                        <p className="text-2xl font-semibold text-gray-900">{summary.overdue ?? 0}</p>
                    </div>
                </div>
                <Button
                    type="button"
                    variant="outline"
                    onClick={() => router.post(remindersAction, {}, { preserveScroll: true })}
                >
                    Run payment reminders
                </Button>
                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <table className="min-w-full text-sm">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="p-3 text-left">ID</th>
                                <th className="p-3 text-left">Org</th>
                                <th className="p-3 text-left">Amount</th>
                                <th className="p-3 text-left">Status</th>
                                <th className="p-3 text-left">Due</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {items.map((invoice) => (
                                <tr key={invoice.id} className="border-t border-gray-100">
                                    <td className="p-3">{invoice.invoice_number}</td>
                                    <td className="p-3">{invoice.organization_name}</td>
                                    <td className="p-3">{invoice.total}</td>
                                    <td className="p-3">{invoice.status}</td>
                                    <td className="p-3">{invoice.due_date}</td>
                                    <td className="p-3 text-right space-x-2">
                                        <a href={invoice.download_href} className="text-primary-700">PDF</a>
                                        {invoice.can_mark_paid ? (
                                            <button
                                                type="button"
                                                className="text-primary-700"
                                                onClick={() =>
                                                    router.post(invoice.pay_action, {}, { preserveScroll: true })
                                                }
                                            >
                                                Mark paid
                                            </button>
                                        ) : null}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </AgencyLayout>
    );
}
