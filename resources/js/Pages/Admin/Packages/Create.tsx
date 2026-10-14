import { PackageFormFields } from '@/components/admin/PackageFormFields';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/Layouts/AdminLayout';
import { Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

export default function Create() {
    const form = useForm({
        name: '',
        slug: '',
        price: '0',
        billing_cycle: 'monthly',
        is_active: true,
    });

    return (
        <AdminLayout title="New Package">
            <div className="space-y-6">
                <Link href={route('admin.packages.index')} className="text-sm text-primary-600">← Packages</Link>
                <h2 className="text-2xl font-bold text-gray-900">New package</h2>
                <form
                    className="max-w-lg space-y-4"
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.post(route('admin.packages.store'));
                    }}
                >
                    <PackageFormFields form={form} />
                    <Button type="submit" disabled={form.processing}>Create</Button>
                </form>
            </div>
        </AdminLayout>
    );
}
