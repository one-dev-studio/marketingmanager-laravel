import { Button } from '@/components/ui/button';
import AgencyLayout from '@/Layouts/AgencyLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface ClientOption {
    id: number;
    name: string;
}

interface ReportListItem {
    id: number;
    name: string;
    status: string;
    created_at: string | null;
    summary: string | null;
}

interface PaginatedReports {
    data: ReportListItem[];
}

export interface AgencyReportsIndexProps {
    agencyId: string;
    clients: ClientOption[];
    reports: PaginatedReports;
}

export default function Index({ agencyId, clients, reports }: AgencyReportsIndexProps) {
    const form = useForm({
        organization_id: clients[0]?.id?.toString() ?? '',
        report_type: 'weekly',
    });
    const items = reports.data ?? [];

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('agency.reports.generate', { agency: agencyId }), { preserveScroll: true });
    };

    return (
        <AgencyLayout title="Reports">
            <div className="space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Client reports</h1>
                <form
                    onSubmit={submit}
                    className="flex flex-wrap gap-3 rounded-lg border border-gray-200 bg-white p-4"
                >
                    <select
                        name="organization_id"
                        required
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.organization_id}
                        onChange={(e) => form.setData('organization_id', e.target.value)}
                    >
                        {clients.map((client) => (
                            <option key={client.id} value={client.id}>{client.name}</option>
                        ))}
                    </select>
                    <select
                        name="report_type"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.report_type}
                        onChange={(e) => form.setData('report_type', e.target.value)}
                    >
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                    </select>
                    <Button type="submit" disabled={form.processing || clients.length === 0}>
                        {form.processing ? 'Generating…' : 'Generate'}
                    </Button>
                </form>
                <div className="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <p className="p-8 text-center text-gray-500">No reports yet.</p>
                    ) : (
                        items.map((report) => (
                            <div key={report.id} className="flex justify-between gap-4 p-4">
                                <div>
                                    <h2 className="font-medium text-gray-900">{report.name}</h2>
                                    <p className="text-sm text-gray-500">
                                        {report.status} · {report.created_at ?? '—'}
                                    </p>
                                    {report.summary && <p className="mt-1 text-sm text-gray-700">{report.summary}</p>}
                                </div>
                                <a
                                    href={route('agency.reports.download', { agency: agencyId, report: report.id })}
                                    className="text-sm text-primary-600 hover:underline"
                                >
                                    Download PDF
                                </a>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AgencyLayout>
    );
}
