import AdminLayout from '@/Layouts/AdminLayout';

interface LogRow {
    level: string | null;
    message: string | null;
    created_at: string | null;
}

interface PaginatedLogs {
    data: LogRow[];
}

export interface AdminLogsIndexProps {
    logs: PaginatedLogs;
    performanceMetrics: Record<string, unknown>;
    filters: Record<string, string | null>;
}

export default function Index({ logs, performanceMetrics }: AdminLogsIndexProps) {
    const rows = logs.data ?? [];
    const metricEntries = Object.entries(performanceMetrics ?? {});

    return (
        <AdminLayout title="System Logs">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">System Logs</h2>
                    <p className="mt-1 text-gray-600">View platform system logs and performance metrics</p>
                </div>
                {metricEntries.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                        {metricEntries.map(([metric, value]) => (
                            <div key={metric} className="rounded-lg border border-gray-200 p-4">
                                <p className="text-sm text-gray-500">{metric.replace(/_/g, ' ')}</p>
                                <p className="text-2xl font-semibold text-gray-900">
                                    {typeof value === 'object' ? JSON.stringify(value) : String(value)}
                                </p>
                            </div>
                        ))}
                    </div>
                ) : null}
                <div className="overflow-hidden rounded-lg border border-gray-200">
                    <table className="min-w-full divide-y divide-gray-200 text-sm">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Level</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Message</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Date</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {rows.length === 0 ? (
                                <tr>
                                    <td colSpan={3} className="px-4 py-8 text-center text-gray-500">No system logs found.</td>
                                </tr>
                            ) : (
                                rows.map((log, index) => (
                                    <tr key={index}>
                                        <td className="px-4 py-3 text-gray-600">{log.level ?? 'info'}</td>
                                        <td className="px-4 py-3 text-gray-900">{log.message ?? ''}</td>
                                        <td className="px-4 py-3 text-gray-600">{log.created_at ?? ''}</td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}
