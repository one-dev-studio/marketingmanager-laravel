import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { ReportListItem } from '@/types/reports';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedReports {
    data: ReportListItem[];
    links: PaginationLink[];
}

export interface ReportsIndexProps {
    organizationId: string;
    reports: PaginatedReports;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, reports, can }: ReportsIndexProps) {
    const items = reports.data ?? [];

    const generate = (reportId: number) => {
        router.post(
            route('main.reports.generate', { organizationId, reportId }),
            {},
            { preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Reports">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">Reports</h1>
                    {can.create ? (
                        <Link href={route('main.reports.create', { organizationId })}>
                            <Button type="button">Builder</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No reports.</div>
                    ) : (
                        items.map((report) => (
                            <div
                                key={report.id}
                                className="flex flex-col gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <Link
                                    href={route('main.reports.show', {
                                        organizationId,
                                        reportId: report.id,
                                    })}
                                    className="font-medium text-gray-900 hover:text-primary-700"
                                >
                                    {report.name}
                                </Link>
                                <div className="flex flex-wrap gap-3 text-sm">
                                    <button
                                        type="button"
                                        className="text-primary-700 hover:text-primary-800"
                                        onClick={() => generate(report.id)}
                                    >
                                        Generate
                                    </button>
                                    <button
                                        type="button"
                                        className="text-gray-700 hover:text-gray-900"
                                        onClick={() =>
                                            router.post(
                                                route('main.reports.export', {
                                                    organizationId,
                                                    reportId: report.id,
                                                }),
                                                { format: 'pdf' },
                                            )
                                        }
                                    >
                                        PDF
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {reports.links?.length ? (
                    <div className="flex flex-wrap gap-2 text-sm">
                        {reports.links.map((link) =>
                            link.url ? (
                                <Link
                                    key={link.label}
                                    href={link.url}
                                    className={
                                        link.active
                                            ? 'font-medium text-primary-700'
                                            : 'text-gray-600 hover:text-gray-900'
                                    }
                                    preserveScroll
                                >
                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                </Link>
                            ) : (
                                <span
                                    key={link.label}
                                    className="text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </div>
                ) : null}
            </div>
        </AppLayout>
    );
}
