import { BrandFormFields, brandFormDefaults, type BrandFormData } from '@/components/brands/BrandFormFields';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface BrandsCreateProps {
    organizationId: string;
    prefillName: string | null;
}

export default function Create({ organizationId, prefillName }: BrandsCreateProps) {
    const form = useForm<BrandFormData>(brandFormDefaults(null, prefillName));

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.brands.store', { organizationId }), {
            forceFormData: form.data.logo !== null,
        });
    };

    return (
        <AppLayout title="Create Brand">
            <div className="mx-auto max-w-3xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Create Brand</h1>
                    <p className="mt-1 text-sm text-gray-600">
                        Add guidelines and tone of voice used by AI content tools
                    </p>
                </div>
                <form onSubmit={submit} className="space-y-4">
                    <div className="rounded-lg border border-gray-200 bg-white p-6">
                        <BrandFormFields
                            data={form.data}
                            errors={form.errors}
                            setData={(key, value) => form.setData(key, value)}
                        />
                    </div>
                    <div className="flex items-center gap-3">
                        <Button type="submit" disabled={form.processing}>Save brand</Button>
                        <Link
                            href={route('main.brands.index', { organizationId })}
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
