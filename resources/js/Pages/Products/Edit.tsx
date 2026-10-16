import {
    ProductFormFields,
    productFormDefaults,
    type ProductFormData,
} from '@/components/products/ProductFormFields';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { ProductDetail, SelectOption } from '@/types/products';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface ProductsEditProps {
    organizationId: string;
    product: ProductDetail;
    categories: SelectOption[];
    brands: SelectOption[];
}

export default function Edit({ organizationId, product, categories, brands }: ProductsEditProps) {
    const form = useForm<ProductFormData>(productFormDefaults(product));

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.transform((data) => ({
            ...data,
            category_id: data.category_id === '' ? null : data.category_id,
            brand_id: data.brand_id === '' ? null : data.brand_id,
        }));
        form.put(route('main.products.update', { organizationId, product: product.id }), {
            forceFormData: form.data.image !== null,
        });
    };

    return (
        <AppLayout title="Edit Product">
            <div className="mx-auto max-w-2xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Edit product</h1>
                    <p className="mt-1 text-sm text-gray-600">{product.name}</p>
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
                        <Button type="submit" disabled={form.processing}>Save changes</Button>
                        <Link
                            href={route('main.products.show', { organizationId, product: product.id })}
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
