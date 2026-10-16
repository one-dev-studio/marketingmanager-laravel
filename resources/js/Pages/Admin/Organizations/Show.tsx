import AdminLayout from '@/Layouts/AdminLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface AdminOrganizationsShowProps {
    organization: {
        id: number;
        name: string;
        slug: string;
        status: string | null;
        created_at: string | null;
    };
}

export default function Show({ organization }: AdminOrganizationsShowProps) {
    return (
        <AdminLayout title={organization.name}>
            <div className="space-y-6">
                <Link href={route('admin.organizations.index')} className="text-sm text-primary-600 hover:text-primary-800">
                    ← Back to organizations
                </Link>
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">{organization.name}</h2>
                    <p className="text-gray-600">{organization.slug}</p>
                </div>
                <dl className="grid gap-4 md:grid-cols-2">
                    <div className="rounded-lg border border-gray-200 p-4">
                        <dt className="text-sm text-gray-500">Status</dt>
                        <dd className="text-lg font-semibold text-gray-900">{organization.status}</dd>
                    </div>
                    <div className="rounded-lg border border-gray-200 p-4">
                        <dt className="text-sm text-gray-500">Created</dt>
                        <dd className="text-lg font-semibold text-gray-900">{organization.created_at ?? '—'}</dd>
                    </div>
                </dl>
            </div>
        </AdminLayout>
    );
}
