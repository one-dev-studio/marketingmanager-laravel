import { Button } from '@/components/ui/button';
import AgencyLayout from '@/Layouts/AgencyLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface AgencyClientsCreateProps {
    agencyId: string;
}

export default function Create({ agencyId }: AgencyClientsCreateProps) {
    const form = useForm({ name: '', slug: '' });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('agency.clients.store', { agency: agencyId }));
    };

    return (
        <AgencyLayout title="Add client">
            <form
                onSubmit={submit}
                className="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6"
            >
                <h1 className="text-2xl font-semibold text-gray-900">Add client</h1>
                <input
                    name="name"
                    required
                    placeholder="Organization name"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.name}
                    onChange={(e) => form.setData('name', e.target.value)}
                />
                <input
                    name="slug"
                    placeholder="slug (optional)"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.slug}
                    onChange={(e) => form.setData('slug', e.target.value)}
                />
                <Button type="submit" disabled={form.processing}>Create & associate</Button>
            </form>
        </AgencyLayout>
    );
}
