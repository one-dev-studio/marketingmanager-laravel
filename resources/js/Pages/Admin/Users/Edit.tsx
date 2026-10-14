import { Button } from '@/components/ui/button';
import AdminLayout from '@/Layouts/AdminLayout';
import { Link, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface AdminUsersEditProps {
    user: {
        id: number;
        name: string;
        email: string;
        status: string | null;
    };
}

const statusOptions = ['active', 'inactive', 'suspended'];

export default function Edit({ user }: AdminUsersEditProps) {
    const form = useForm({
        name: user.name,
        status: user.status ?? 'active',
    });

    return (
        <AdminLayout title="Edit User">
            <div className="space-y-6">
                <div>
                    <Link href={route('admin.users.show', user.id)} className="text-sm text-primary-600 hover:text-primary-800">
                        ← Back to user
                    </Link>
                    <h2 className="mt-2 text-2xl font-bold text-gray-900">Edit User</h2>
                    <p className="text-gray-600">{user.email}</p>
                </div>
                <form
                    className="max-w-xl space-y-4"
                    onSubmit={(event) => {
                        event.preventDefault();
                        form.put(route('admin.users.update', user.id));
                    }}
                >
                    <div>
                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">Name</label>
                        <input
                            id="name"
                            name="name"
                            value={form.data.name}
                            onChange={(event) => form.setData('name', event.target.value)}
                            className="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm"
                        />
                    </div>
                    <div>
                        <label htmlFor="status" className="block text-sm font-medium text-gray-700">Status</label>
                        <select
                            id="status"
                            name="status"
                            value={form.data.status}
                            onChange={(event) => form.setData('status', event.target.value)}
                            className="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm"
                        >
                            {statusOptions.map((status) => (
                                <option key={status} value={status}>{status.charAt(0).toUpperCase() + status.slice(1)}</option>
                            ))}
                        </select>
                    </div>
                    <Button type="submit" disabled={form.processing}>Save Changes</Button>
                </form>
            </div>
        </AdminLayout>
    );
}
