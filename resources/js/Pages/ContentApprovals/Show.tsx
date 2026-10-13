import AppLayout from '@/Layouts/AppLayout';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface ApprovalHistoryItem {
    id: number;
    status: string;
    comments: string | null;
    reviewed_at: string | null;
    approver_name: string | null;
}

export interface ContentApprovalShowData {
    id: number;
    status: string;
    comments: string | null;
    campaign_name: string | null;
    channel_name: string | null;
    content: string;
    scheduled_at: string | null;
    can: {
        approve: boolean;
    };
    history: ApprovalHistoryItem[];
}

export interface ContentApprovalsShowProps {
    organizationId: string;
    approval: ContentApprovalShowData;
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

export default function Show({ organizationId, approval }: ContentApprovalsShowProps) {
    const approve = () => {
        router.post(route('main.content-approvals.approve', { organizationId, approval: approval.id }));
    };

    const reject = () => {
        router.post(route('main.content-approvals.reject', { organizationId, approval: approval.id }), {
            rejection_reason: 'Rejected from review screen',
        });
    };

    return (
        <AppLayout title="Content approval">
            <div className="mx-auto max-w-4xl space-y-6">
                <div>
                    <Link
                        href={route('main.content-approvals.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        ← Back to queue
                    </Link>
                    <h1 className="mt-2 text-2xl font-bold text-gray-900">Content approval</h1>
                    <p className="text-sm text-gray-600">Review and approve scheduled content</p>
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-6">
                    <div className="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 className="text-lg font-semibold text-gray-900">
                                {approval.campaign_name ?? 'Scheduled post'}
                            </h2>
                            <p className="text-sm text-gray-500">Channel: {approval.channel_name ?? 'N/A'}</p>
                        </div>
                        <span className={`rounded-full px-3 py-1 text-sm font-medium ${statusClass(approval.status)}`}>
                            {approval.status}
                        </span>
                    </div>

                    <div className="mb-6">
                        <h3 className="mb-2 font-semibold text-gray-900">Content</h3>
                        <div className="rounded-lg bg-gray-50 p-4">
                            <p className="whitespace-pre-wrap text-gray-800">{approval.content}</p>
                        </div>
                    </div>

                    {approval.scheduled_at ? (
                        <div className="mb-6">
                            <h3 className="mb-2 font-semibold text-gray-900">Scheduled</h3>
                            <p className="text-gray-700">{new Date(approval.scheduled_at).toLocaleString()}</p>
                        </div>
                    ) : null}

                    {approval.comments ? (
                        <div className="mb-6">
                            <h3 className="mb-2 font-semibold text-gray-900">Comments</h3>
                            <p className="text-gray-700">{approval.comments}</p>
                        </div>
                    ) : null}

                    {approval.can.approve && approval.status === 'pending' ? (
                        <div className="flex gap-3">
                            <button
                                type="button"
                                className="rounded-md bg-primary-600 px-4 py-2 text-sm text-white"
                                onClick={approve}
                            >
                                Approve
                            </button>
                            <button
                                type="button"
                                className="rounded-md bg-red-600 px-4 py-2 text-sm text-white"
                                onClick={reject}
                            >
                                Reject
                            </button>
                        </div>
                    ) : null}
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-6">
                    <h3 className="mb-4 text-lg font-semibold text-gray-900">Approval history</h3>
                    <ul className="space-y-3">
                        {approval.history.map((item) => (
                            <li key={item.id} className="rounded-lg bg-gray-50 p-3">
                                <div className="flex items-center justify-between">
                                    <span className="font-medium">{item.approver_name ?? 'Reviewer'}</span>
                                    {item.reviewed_at ? (
                                        <span className="text-xs text-gray-500">
                                            {new Date(item.reviewed_at).toLocaleString()}
                                        </span>
                                    ) : null}
                                </div>
                                <p className="text-sm capitalize text-gray-700">{item.status}</p>
                                {item.comments ? (
                                    <p className="mt-1 text-sm text-gray-600">{item.comments}</p>
                                ) : null}
                            </li>
                        ))}
                    </ul>
                </div>
            </div>
        </AppLayout>
    );
}
