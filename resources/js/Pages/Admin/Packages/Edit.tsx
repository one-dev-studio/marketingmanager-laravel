import { PackageFormFields } from '@/components/admin/PackageFormFields';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/Layouts/AdminLayout';
import { Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface AdminPackagesEditProps {
    package: {
        id: number;
        name: string;
        slug: string;
        price: string | number;
        billing_cycle: string;
        is_active: boolean;
    };
}

export default function Edit({ package: packageRow }: AdminPackagesEditProps) {
    const form = useForm({
        name: packageRow.name,
        slug: packageRow.slug,
        price: String(packageRow.price),
        billing_cycle: packageRow.billing_cycle,
        is_active: packageRow.is_active,
    });

    return (
        <AdminLayout title={`Edit ${packageRow.name}`}>
            <div className="space-y-6">
                <Link href={route('admin.packages.index')} className="text-sm text-primary-600">← Packages</Link>
                <h2 className="text-2xl font-bold text-gray-900">Edit package</h2>
                <form
                    className="max-w-lg space-y-4"
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.put(route('admin.packages.update', packageRow.id));
                    }}
                >
                    <PackageFormFields form={form} />
                    <Button type="submit" disabled={form.processing}>Save</Button>
                </form>
            </div>
        </AdminLayout>
    );
}
