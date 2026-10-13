import {
    ProductFormFields,
    productFormDefaults,
    type ProductFormData,
} from '@/components/products/ProductFormFields';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { SelectOption } from '@/types/products';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface ProductsCreateProps {
    organizationId: string;
    categories: SelectOption[];
    brands: SelectOption[];
}

export default function Create({ organizationId, categories, brands }: ProductsCreateProps) {
    const form = useForm<ProductFormData>(productFormDefaults(null));

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.transform((data) => ({
            ...data,
            category_id: data.category_id === '' ? null : data.category_id,
            brand_id: data.brand_id === '' ? null : data.brand_id,
        }));
        form.post(route('main.products.store', { organizationId }), {
            forceFormData: form.data.image !== null,
        });
    };

    return (
        <AppLayout title="Add Product">
            <div className="mx-auto max-w-2xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Add product</h1>
                </div>
                <form onSubmit={submit} className="space-y-4">
                    <div className="rounded-lg border border-gray-200 bg-white p-6">
                        <ProductFormFields
                            data={form.data}
                            errors={form.errors}
                            setData={(key, value) => form.setData(key, value)}
                            categories={categories}
                            brands={brands}
                        />
                    </div>
                    <div className="flex items-center gap-3">
                        <Button type="submit" disabled={form.processing}>Save product</Button>
                        <Link
                            href={route('main.products.index', { organizationId })}
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
