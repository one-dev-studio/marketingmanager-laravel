import AdminLayout from '@/Layouts/AdminLayout';

interface AdminUserRow {
    id: number;
    name: string;
    email: string;
    user_type: string | null;
}

export interface AdminTeamIndexProps {
    admins: AdminUserRow[];
}

export default function Index({ admins }: AdminTeamIndexProps) {
    return (
        <AdminLayout title="Agency Team">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Agency Team</h2>
                    <p className="text-gray-600">Platform and agency administrators</p>
                </div>
                <ul className="divide-y rounded-lg border border-gray-200">
                    {admins.length === 0 ? (
                        <li className="p-8 text-center text-gray-500">No admin users found.</li>
                    ) : (
                        admins.map((admin) => (
                            <li key={admin.id} className="flex justify-between p-4 text-sm">
                                <div>
                                    <p className="font-medium text-gray-900">{admin.name}</p>
                                    <p className="text-gray-600">{admin.email}</p>
                                </div>
                                <span className="text-gray-500">{admin.user_type}</span>
                            </li>
                        ))
                    )}
                </ul>
            </div>
        </AdminLayout>
    );
}
