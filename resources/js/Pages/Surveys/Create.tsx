import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface SurveysCreateProps {
    organizationId: string;
}

export default function Create({ organizationId }: SurveysCreateProps) {
    const form = useForm({
        title: '',
        description: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.surveys.store', { organizationId }));
    };

    return (
        <AppLayout title="Create survey">
            <div className="mx-auto max-w-xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Create survey</h1>
                    <p className="mt-1 text-sm text-gray-600">You will open the builder after saving.</p>
                </div>
                <form
                    onSubmit={submit}
                    className="space-y-3 rounded-lg border border-gray-200 bg-white p-6"
                >
                    <div>
                        <label className="block text-sm font-medium text-gray-700" htmlFor="survey-title">
                            Title
                        </label>
                        <input
                            id="survey-title"
                            name="title"
                            required
                            value={form.data.title}
                            className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            onChange={(event) => form.setData('title', event.target.value)}
                        />
                        {form.errors.title ? (
                            <p className="mt-1 text-sm text-red-600">{form.errors.title}</p>
                        ) : null}
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700" htmlFor="survey-description">
                            Description
                        </label>
                        <textarea
                            id="survey-description"
                            name="description"
                            rows={3}
                            value={form.data.description}
                            className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            onChange={(event) => form.setData('description', event.target.value)}
                        />
                        {form.errors.description ? (
                            <p className="mt-1 text-sm text-red-600">{form.errors.description}</p>
                        ) : null}
                    </div>
                    <div className="flex items-center gap-3 pt-2">
                        <Button type="submit" disabled={form.processing}>
                            Create &amp; open builder
                        </Button>
                        <Link
                            href={route('main.surveys.index', { organizationId })}
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
