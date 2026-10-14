import AdminLayout from '@/Layouts/AdminLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface UserRow {
    id: number;
    name: string;
    email: string;
    user_type: string | null;
    status: string | null;
}

interface PaginatedUsers {
    data: UserRow[];
}

export interface AdminUsersIndexProps {
    users: PaginatedUsers;
    filters: Record<string, string | null>;
}

export default function Index({ users }: AdminUsersIndexProps) {
    const rows = users.data ?? [];

    return (
        <AdminLayout title="Users">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Users</h2>
                    <p className="mt-1 text-gray-600">Manage platform users</p>
                </div>
                <div className="overflow-hidden rounded-lg border border-gray-200">
                    <table className="min-w-full divide-y divide-gray-200 text-sm">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Email</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Type</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                <th className="px-4 py-3" />
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {rows.length === 0 ? (
                                <tr>
                                    <td colSpan={5} className="px-4 py-8 text-center text-gray-500">No users found.</td>
                                </tr>
                            ) : (
                                rows.map((user) => (
                                    <tr key={user.id}>
                                        <td className="px-4 py-3 text-gray-900">{user.name}</td>
                                        <td className="px-4 py-3 text-gray-600">{user.email}</td>
                                        <td className="px-4 py-3 text-gray-600">{user.user_type}</td>
                                        <td className="px-4 py-3 text-gray-600">{user.status}</td>
                                        <td className="px-4 py-3">
                                            <Link
                                                href={route('admin.users.show', user.id)}
                                                className="text-primary-600 hover:text-primary-800"
                                            >
                                                View
                                            </Link>
                                        </td>
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
