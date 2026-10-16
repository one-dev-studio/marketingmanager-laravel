import AppLayout from '@/Layouts/AppLayout';
import { paginationLabelText } from '@/lib/paginationLabel';
import type { ContentApprovalListItem } from '@/types/content-workflow';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedApprovals {
    data: ContentApprovalListItem[];
    links: PaginationLink[];
}

export interface ContentApprovalsIndexProps {
    organizationId: string;
    filters: {
        status: string | null;
        channel_id: string | null;
        author_id: string | null;
        date: string | null;
    };
    approvals: PaginatedApprovals;
}

function statusClass(status: string): string {
    const map: Record<string, string> = {
        pending: 'bg-yellow-100 text-yellow-800',
        approved: 'bg-green-100 text-green-800',
        rejected: 'bg-red-100 text-red-800',
        changes_requested: 'bg-orange-100 text-orange-800',
    };
    return map[status] ?? 'bg-gray-100 text-gray-800';
}

export default function Index({ organizationId, filters, approvals }: ContentApprovalsIndexProps) {
    const items = approvals.data ?? [];

    const updateFilter = (key: keyof ContentApprovalsIndexProps['filters'], value: string) => {
        router.get(
            route('main.content-approvals.index', { organizationId }),
            {
                ...filters,
                [key]: value || undefined,
            },
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Content Review">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Content Review</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Review and approve content for publication
                        </p>
                    </div>
                    <Link
                        href={route('main.content-calendar.index', { organizationId })}
                        className="text-sm font-medium text-primary-600 hover:text-primary-800"
                    >
                        Content calendar
                    </Link>
                </div>

                <div className="grid grid-cols-1 gap-4 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-2">
                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">Status</label>
                        <select
                            value={filters.status ?? ''}
                            onChange={(e) => updateFilter('status', e.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        >
                            <option value="">All statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="changes_requested">Changes requested</option>
                        </select>
                    </div>
                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">Requested date</label>
                        <input
                            type="date"
                            value={filters.date ?? ''}
                            onChange={(e) => updateFilter('date', e.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        />
                    </div>
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No content approvals found.</div>
                    ) : (
                        <ul className="divide-y divide-gray-100">
                            {items.map((approval) => (
                                <li
                                    key={approval.id}
                                    className="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between"
                                >
                                    <div className="min-w-0 flex-1">
                                        <div className="flex flex-wrap items-center gap-2">
                                            <span
                                                className={`rounded-full px-2 py-0.5 text-xs font-medium ${statusClass(approval.status)}`}
                                            >
                                                {approval.status}
                                            </span>
                                            {approval.campaign_name ? (
                                                <span className="text-sm text-gray-500">
                                                    {approval.campaign_name}
                                                </span>
                                            ) : null}
                                        </div>
                                        <p className="mt-1 text-sm text-gray-900">{approval.content_preview}</p>
                                        <p className="mt-1 text-xs text-gray-500">
                                            {approval.channel_name ?? 'Channel'}
                                            {approval.requested_by_name
                                                ? ` · ${approval.requested_by_name}`
                                                : ''}
                                        </p>
                                    </div>
                                    <Link
                                        href={approval.show_href}
                                        className="text-sm font-medium text-primary-600 hover:text-primary-800"
                                    >
                                        Review
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>

                {approvals.links && approvals.links.length > 1 ? (
                    <nav className="flex flex-wrap gap-2" aria-label="Pagination">
                        {approvals.links.map((link) =>
                            link.url ? (
                                <Link
                                    key={`${link.label}-${link.url}`}
                                    href={link.url}
                                    className={`rounded border px-3 py-1 text-sm ${
                                        link.active
                                            ? 'border-primary-600 bg-primary-50 text-primary-700'
                                            : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                    }`}
                                    preserveScroll
                                >
                                    <span>{paginationLabelText(link.label)}</span>
                                </Link>
                            ) : (
                                <span
                                    key={link.label}
                                    className="rounded border border-gray-200 px-3 py-1 text-sm text-gray-400">
                                        {paginationLabelText(link.label)}
                                    </span>
                            ),
                        )}
                    </nav>
                ) : null}
            </div>
        </AppLayout>
    );
}
