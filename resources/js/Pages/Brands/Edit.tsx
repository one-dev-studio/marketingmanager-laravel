import { BrandFormFields, brandFormDefaults, type BrandFormData } from '@/components/brands/BrandFormFields';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { BrandDetail } from '@/types/brands';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface BrandsEditProps {
    organizationId: string;
    brand: BrandDetail;
}

export default function Edit({ organizationId, brand }: BrandsEditProps) {
    const form = useForm<BrandFormData>(brandFormDefaults(brand, null));

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.put(route('main.brands.update', { organizationId, brand: brand.id }), {
            forceFormData: form.data.logo !== null,
        });
    };

    return (
        <AppLayout title="Edit Brand">
            <div className="mx-auto max-w-3xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Edit Brand</h1>
                    <p className="mt-1 text-sm text-gray-600">{brand.name}</p>
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
                        <Button type="submit" disabled={form.processing}>Save changes</Button>
                        <Link
                            href={route('main.brands.show', { organizationId, brand: brand.id })}
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
