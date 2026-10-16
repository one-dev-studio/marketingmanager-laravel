import { ReportBuilderPanel } from '@/components/reports/ReportBuilderPanel';
import AppLayout from '@/Layouts/AppLayout';
import type { ReportBuilderData } from '@/types/reports';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ReportsBuilderProps {
    organizationId: string;
    report: ReportBuilderData;
}

export default function Builder({ organizationId, report }: ReportsBuilderProps) {
    const title = report.id ? `Report — ${report.name}` : 'Report builder';

    return (
        <AppLayout title={title}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">{title}</h1>
                    <Link
                        href={route('main.reports.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>
                <ReportBuilderPanel organizationId={organizationId} report={report} />
            </div>
        </AppLayout>
    );
}
