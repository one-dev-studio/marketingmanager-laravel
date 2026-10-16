import { Button } from '@/components/ui/button';
import AdminLayout from '@/Layouts/AdminLayout';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PackageRow {
    id: number;
    name: string;
    billing_cycle: string;
    price: string | number;
    is_active: boolean;
}

export interface AdminPackagesIndexProps {
    packages: PackageRow[];
}

export default function Index({ packages }: AdminPackagesIndexProps) {
    return (
        <AdminLayout title="Packages">
            <div className="space-y-4">
                <div className="flex justify-between">
                    <h2 className="text-2xl font-bold text-gray-900">Packages</h2>
                    <Button asChild>
                        <Link href={route('admin.packages.create')}>New package</Link>
                    </Button>
                </div>
                <div className="divide-y rounded-lg border border-gray-200 bg-white">
                    {packages.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No packages.</div>
                    ) : (
                        packages.map((packageRow) => (
                            <div key={packageRow.id} className="flex items-center justify-between p-4">
                                <div>
                                    <h3 className="font-medium text-gray-900">{packageRow.name}</h3>
                                    <p className="text-sm text-gray-500">
                                        {packageRow.billing_cycle} · {packageRow.price} ·{' '}
                                        {packageRow.is_active ? 'Active' : 'Inactive'}
                                    </p>
                                </div>
                                <div className="flex gap-3 text-sm">
                                    <Link href={route('admin.packages.edit', packageRow.id)} className="text-primary-600">
                                        Edit
                                    </Link>
                                    <button
                                        type="button"
                                        className="text-red-600"
                                        onClick={() => {
                                            if (window.confirm('Delete this package?')) {
                                                router.delete(route('admin.packages.destroy', packageRow.id));
                                            }
                                        }}
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
