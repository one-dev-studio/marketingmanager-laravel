import AdminLayout from '@/Layouts/AdminLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface ActivityLogRow {
    action: string | null;
    created_at: string | null;
}

export interface AdminUsersShowProps {
    user: {
        id: number;
        name: string;
        email: string;
        user_type: string | null;
        status: string | null;
        email_verified_at: string | null;
    };
    activityLogs: ActivityLogRow[];
}

export default function Show({ user, activityLogs }: AdminUsersShowProps) {
    return (
        <AdminLayout title={user.name}>
            <div className="space-y-6">
                <div className="flex items-start justify-between">
                    <div>
                        <Link href={route('admin.users.index')} className="text-sm text-primary-600 hover:text-primary-800">
                            ← Back to users
                        </Link>
                        <h2 className="mt-2 text-2xl font-bold text-gray-900">{user.name}</h2>
                        <p className="text-gray-600">{user.email}</p>
                    </div>
                    <Link href={route('admin.users.edit', user.id)} className="text-sm text-primary-600 hover:text-primary-800">
                        Edit
                    </Link>
                </div>
                <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">User Type</p>
                        <p className="text-lg font-semibold text-gray-900">{user.user_type}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Status</p>
                        <p className="text-lg font-semibold text-gray-900">{user.status}</p>
                    </div>
                    <div className="rounded-lg border border-gray-200 p-4">
                        <p className="text-sm text-gray-500">Email Verified</p>
                        <p className="text-lg font-semibold text-gray-900">{user.email_verified_at ? 'Yes' : 'No'}</p>
                    </div>
                </div>
                <div className="rounded-lg border border-gray-200 p-4">
                    <h3 className="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    {activityLogs.length === 0 ? (
                        <p className="mt-2 text-sm text-gray-500">No activity logs recorded.</p>
                    ) : (
                        <ul className="mt-4 space-y-2">
                            {activityLogs.map((log, index) => (
                                <li key={index} className="text-sm text-gray-600">
                                    {log.action ?? 'Activity'} · {log.created_at ?? ''}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
