import AdminLayout from '@/Layouts/AdminLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface OrganizationRow {
    id: number;
    name: string;
    slug: string;
    status: string | null;
}

interface PaginatedOrganizations {
    data: OrganizationRow[];
    links: { url: string | null; label: string; active: boolean }[];
}

export interface AdminOrganizationsIndexProps {
    organizations: PaginatedOrganizations;
}

export default function Index({ organizations }: AdminOrganizationsIndexProps) {
    const rows = organizations.data ?? [];

    return (
        <AdminLayout title="Organizations">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Organizations</h2>
                    <p className="mt-1 text-gray-600">Manage platform organizations</p>
                </div>
                <div className="overflow-hidden rounded-lg border border-gray-200">
                    <table className="min-w-full divide-y divide-gray-200 text-sm">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Slug</th>
                                <th className="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                <th className="px-4 py-3" />
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {rows.length === 0 ? (
                                <tr>
                                    <td colSpan={4} className="px-4 py-8 text-center text-gray-500">
                                        No organizations found.
                                    </td>
                                </tr>
                            ) : (
                                rows.map((organization) => (
                                    <tr key={organization.id}>
                                        <td className="px-4 py-3 text-gray-900">{organization.name}</td>
                                        <td className="px-4 py-3 text-gray-600">{organization.slug}</td>
                                        <td className="px-4 py-3 text-gray-600">{organization.status}</td>
                                        <td className="px-4 py-3">
                                            <Link
                                                href={route('admin.organizations.show', organization.id)}
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
