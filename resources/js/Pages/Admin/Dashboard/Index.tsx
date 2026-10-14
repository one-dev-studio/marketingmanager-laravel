import AdminLayout from '@/Layouts/AdminLayout';

export interface AdminDashboardIndexProps {
    adminName: string;
    stats: {
        organizations: number;
        users: number;
        campaigns: number;
    };
}

export default function Index({ adminName, stats }: AdminDashboardIndexProps) {
    return (
        <AdminLayout title="Admin Dashboard">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
                    <p className="mt-1 text-gray-600">Welcome back, {adminName}.</p>
                </div>
                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Organizations</p>
                        <p className="text-2xl font-semibold text-gray-900">{stats.organizations}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Users</p>
                        <p className="text-2xl font-semibold text-gray-900">{stats.users}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Campaigns</p>
                        <p className="text-2xl font-semibold text-gray-900">{stats.campaigns}</p>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
