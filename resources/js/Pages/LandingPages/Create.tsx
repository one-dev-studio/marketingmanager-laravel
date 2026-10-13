import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface LandingPagesCreateProps {
    organizationId: string;
}

export default function Create({ organizationId }: LandingPagesCreateProps) {
    const form = useForm({
        name: '',
        slug: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.landing-pages.store', { organizationId }));
    };

    return (
        <AppLayout title="Create landing page">
            <div className="mx-auto max-w-xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Create landing page</h1>
                    <p className="mt-1 text-sm text-gray-600">You will open the builder after saving.</p>
                </div>
                <form
                    onSubmit={submit}
                    className="space-y-3 rounded-lg border border-gray-200 bg-white p-6"
                >
                    <div>
                        <label className="block text-sm font-medium text-gray-700" htmlFor="lp-name">
                            Name
                        </label>
                        <input
                            id="lp-name"
                            name="name"
                            required
                            value={form.data.name}
                            className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            onChange={(event) => form.setData('name', event.target.value)}
                        />
                        {form.errors.name ? (
                            <p className="mt-1 text-sm text-red-600">{form.errors.name}</p>
                        ) : null}
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700" htmlFor="lp-slug">
                            Slug (optional)
                        </label>
                        <input
                            id="lp-slug"
                            name="slug"
                            value={form.data.slug}
                            className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            onChange={(event) => form.setData('slug', event.target.value)}
                        />
                        {form.errors.slug ? (
                            <p className="mt-1 text-sm text-red-600">{form.errors.slug}</p>
                        ) : null}
                    </div>
                    <div className="flex items-center gap-3 pt-2">
                        <Button type="submit" disabled={form.processing}>
                            Create &amp; open builder
                        </Button>
                        <Link
                            href={route('main.landing-pages.index', { organizationId })}
                            className="text-sm text-gray-600 hover:text-gray-900"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
