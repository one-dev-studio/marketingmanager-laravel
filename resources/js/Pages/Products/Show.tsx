import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { ProductDetail } from '@/types/products';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ProductsShowProps {
    organizationId: string;
    product: ProductDetail;
}

export default function Show({ organizationId, product }: ProductsShowProps) {
    const deleteProduct = () => {
        if (!window.confirm('Delete this product?')) {
            return;
        }
        router.delete(route('main.products.destroy', { organizationId, product: product.id }));
    };

    return (
        <AppLayout title={product.name}>
            <div className="mx-auto max-w-2xl space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{product.name}</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            {product.sku} · {product.status}
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        {product.can.update ? (
                            <Link
                                href={route('main.products.edit', {
                                    organizationId,
                                    product: product.id,
                                })}
                            >
                                <Button type="button">Edit</Button>
                            </Link>
                        ) : null}
                        {product.can.delete ? (
                            <button
                                type="button"
                                onClick={deleteProduct}
                                className="text-sm text-red-600 hover:text-red-800"
                            >
                                Delete
                            </button>
                        ) : null}
                    </div>
                </div>

                <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Price</h2>
                        <p className="mt-1 text-gray-900">${product.price}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Stock</h2>
                        <p className="mt-1 text-gray-900">{product.stock}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Brand</h2>
                        <p className="mt-1 text-gray-900">{product.brand_name ?? '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Category</h2>
                        <p className="mt-1 text-gray-900">{product.category_name ?? '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Variants</h2>
                        <p className="mt-1 text-gray-900">{product.variants_count}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Description</h2>
                        <p className="mt-1 whitespace-pre-wrap text-gray-900">
                            {product.description || '—'}
                        </p>
                    </div>
                </div>

                <Link
                    href={route('main.products.index', { organizationId })}
                    className="text-sm text-primary-600 hover:text-primary-800"
                >
                    Back to products
                </Link>
            </div>
        </AppLayout>
    );
}
