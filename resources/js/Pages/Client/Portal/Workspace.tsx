import ClientPortalLayout from '@/Layouts/ClientPortalLayout';
import type { ClientPortalBranding } from '@/types/client-portal';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ClientPortalCampaignSummary {
    id: number;
    name: string;
    status: string;
    start_date: string | null;
    end_date: string | null;
}

export interface ClientPortalReportSummary {
    id: number;
    name: string;
    type: string | null;
    last_generated_at: string | null;
}

export interface ClientPortalWorkspaceProps {
    agency: { id: number; name: string };
    organization: { id: string; name: string };
    portalBranding: ClientPortalBranding;
    campaigns: ClientPortalCampaignSummary[];
    reports: ClientPortalReportSummary[];
}

function formatDate(value: string | null): string {
    if (!value) {
        return '—';
    }
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }
    return date.toLocaleDateString();
}

export default function Workspace({
    agency,
    organization,
    portalBranding,
    campaigns,
    reports,
}: ClientPortalWorkspaceProps) {
    return (
        <ClientPortalLayout title={organization.name} portalBranding={portalBranding}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p className="text-sm text-gray-600">
                            <Link
                                href={route('client-portal.home', { agency: agency.id })}
                                className="font-medium"
                                style={{ color: 'var(--client-portal-primary)' }}
                            >
                                {portalBranding.title}
                            </Link>
                            <span className="mx-2 text-gray-400">/</span>
                            {organization.name}
                        </p>
                        <h1 className="text-2xl font-semibold text-gray-900">Workspace overview</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Read-only campaign and report status for your account.
                        </p>
                    </div>
                </div>

                <section className="rounded-lg border border-gray-200 bg-white p-6">
                    <h2 className="text-lg font-medium text-gray-900">Campaigns</h2>
                    {campaigns.length === 0 ? (
                        <p className="mt-3 text-sm text-gray-600">No campaigns to display.</p>
                    ) : (
                        <ul className="mt-4 divide-y divide-gray-100">
                            {campaigns.map((campaign) => (
                                <li
                                    key={campaign.id}
                                    className="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between"
                                >
                                    <div>
                                        <p className="font-medium text-gray-900">{campaign.name}</p>
                                        <p className="text-xs text-gray-500">
                                            {formatDate(campaign.start_date)} — {formatDate(campaign.end_date)}
                                        </p>
                                    </div>
                                    <span className="text-sm font-medium capitalize text-gray-700">
                                        {campaign.status}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    )}
                </section>

                <section className="rounded-lg border border-gray-200 bg-white p-6">
                    <h2 className="text-lg font-medium text-gray-900">Reports</h2>
                    {reports.length === 0 ? (
                        <p className="mt-3 text-sm text-gray-600">No reports to display.</p>
                    ) : (
                        <ul className="mt-4 divide-y divide-gray-100">
                            {reports.map((report) => (
                                <li
                                    key={report.id}
                                    className="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between"
                                >
                                    <div>
                                        <p className="font-medium text-gray-900">{report.name}</p>
                                        <p className="text-xs text-gray-500">
                                            {report.type ?? 'Report'}
                                        </p>
                                    </div>
                                    <span className="text-sm text-gray-600">
                                        {report.last_generated_at
                                            ? `Generated ${formatDate(report.last_generated_at)}`
                                            : 'Not generated yet'}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    )}
                </section>
            </div>
        </ClientPortalLayout>
    );
}
