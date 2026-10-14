import AdminLayout from '@/Layouts/AdminLayout';

interface ContentFlagRow {
    id: number;
    reason: string | null;
    created_at: string | null;
}

interface PaginatedFlags {
    data: ContentFlagRow[];
    meta?: { total: number };
}

export interface AdminContentIndexProps {
    moderationQueueTotal: number;
    contentFlags: PaginatedFlags;
    filters: Record<string, string | null>;
}

export default function Index({ moderationQueueTotal, contentFlags }: AdminContentIndexProps) {
    const flags = contentFlags.data ?? [];

    return (
        <AdminLayout title="Content Moderation">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Content Moderation</h2>
                    <p className="mt-1 text-gray-600">Review flagged content and moderation queue</p>
                </div>
                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Moderation Queue</p>
                        <p className="text-2xl font-semibold text-gray-900">{moderationQueueTotal}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Pending Flags</p>
                        <p className="text-2xl font-semibold text-gray-900">{contentFlags.meta?.total ?? flags.length}</p>
                    </div>
                </div>
                <div className="rounded-lg border border-gray-200 p-4">
                    <h3 className="text-lg font-semibold text-gray-900">Pending Flags</h3>
                    {flags.length === 0 ? (
                        <p className="mt-2 text-sm text-gray-500">No pending content flags.</p>
                    ) : (
                        <ul className="mt-4 divide-y divide-gray-100">
                            {flags.map((flag) => (
                                <li key={flag.id} className="py-3 text-sm text-gray-600">
                                    Flag #{flag.id} · {flag.reason ?? 'No reason'} · {flag.created_at ?? ''}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
