import AdminLayout from '@/Layouts/AdminLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface AdminPackagesShowProps {
    package: {
        id: number;
        name: string;
        slug: string;
        price: string | number;
        billing_cycle: string;
        is_active: boolean;
    };
}

export default function Show({ package: packageRow }: AdminPackagesShowProps) {
    return (
        <AdminLayout title={packageRow.name}>
            <div className="space-y-4">
                <Link href={route('admin.packages.index')} className="text-sm text-primary-600">← Packages</Link>
                <h2 className="text-2xl font-bold text-gray-900">{packageRow.name}</h2>
                <dl className="space-y-2 text-sm">
                    <div><dt className="text-gray-500">Slug</dt><dd>{packageRow.slug}</dd></div>
                    <div><dt className="text-gray-500">Price</dt><dd>{packageRow.price}</dd></div>
                    <div><dt className="text-gray-500">Billing</dt><dd>{packageRow.billing_cycle}</dd></div>
                    <div><dt className="text-gray-500">Active</dt><dd>{packageRow.is_active ? 'Yes' : 'No'}</dd></div>
                </dl>
                <Link href={route('admin.packages.edit', packageRow.id)} className="text-primary-600">Edit</Link>
            </div>
        </AdminLayout>
    );
}
