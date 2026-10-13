import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { ProductListItem, SelectOption } from '@/types/products';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedProducts {
    data: ProductListItem[];
    links: PaginationLink[];
}

export interface ProductsIndexProps {
    organizationId: string;
    products: PaginatedProducts;
    categories: SelectOption[];
    filters: {
        search?: string;
        category_id?: string;
        status?: string;
    };
    can: {
        create: boolean;
    };
}

export default function Index({
    organizationId,
    products,
    categories,
    filters,
    can,
}: ProductsIndexProps) {
    const items = products.data ?? [];

    const deleteProduct = (productId: number) => {
        if (!window.confirm('Delete this product?')) {
            return;
        }
        router.delete(route('main.products.destroy', { organizationId, product: productId }));
    };

    return (
        <AppLayout title="Products">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Products</h1>
                        <p className="mt-1 text-sm text-gray-600">Catalog, pricing, and stock</p>
                    </div>
                    {can.create ? (
                        <Link href={route('main.products.create', { organizationId })}>
                            <Button type="button">Add product</Button>
                        </Link>
                    ) : null}
                </div>

                <form
                    method="get"
                    action={route('main.products.index', { organizationId })}
                    className="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-white p-4"
                >
                    <div>
                        <label className="text-xs font-medium text-gray-500" htmlFor="search">
                            Search
                        </label>
                        <input
                            id="search"
                            name="search"
                            value={filters.search ?? ''}
                            className="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                            placeholder="Name or SKU"
                        />
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500" htmlFor="category_id">
                            Category
                        </label>
                        <select
                            id="category_id"
                            name="category_id"
                            value={filters.category_id ?? ''}
                            className="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                        >
                            <option value="">All</option>
                            {categories.map((category) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500" htmlFor="status">
                            Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            value={filters.status ?? ''}
                            className="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                        >
                            <option value="">All</option>
                            <option value="active">active</option>
                            <option value="inactive">inactive</option>
                            <option value="draft">draft</option>
                        </select>
                    </div>
                    <Button type="submit">Filter</Button>
                </form>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No products yet.</div>
                    ) : (
                        items.map((product) => (
                            <div
                                key={product.id}
                                className="flex items-start justify-between gap-4 border-b border-gray-100 p-4 last:border-b-0 hover:bg-gray-50"
                            >
                                <div className="min-w-0">
                                    <Link
                                        href={route('main.products.show', {
                                            organizationId,
                                            product: product.id,
                                        })}
                                        className="font-medium text-gray-900 hover:text-primary-700"
                                    >
                                        {product.name}
                                    </Link>
                                    <p className="mt-1 text-sm text-gray-500">
                                        {product.sku} · {product.status} · ${product.price} · stock{' '}
                                        {product.stock}
                                    </p>
                                    {(product.brand_name || product.category_name) && (
                                        <p className="mt-1 text-sm text-gray-400">
                                            {[product.brand_name, product.category_name]
                                                .filter(Boolean)
                                                .join(' · ')}
                                        </p>
                                    )}
                                </div>
                                <div className="flex shrink-0 items-center gap-3">
                                    {product.can.update ? (
                                        <Link
                                            href={route('main.products.edit', {
                                                organizationId,
                                                product: product.id,
                                            })}
                                            className="text-sm text-primary-600 hover:text-primary-800"
                                        >
                                            Edit
                                        </Link>
                                    ) : null}
                                    {product.can.delete ? (
                                        <button
                                            type="button"
                                            onClick={() => deleteProduct(product.id)}
                                            className="text-sm text-red-600 hover:text-red-800"
                                        >
                                            Delete
                                        </button>
                                    ) : null}
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {products.links?.length > 1 ? (
                    <div className="flex flex-wrap gap-2">
                        {products.links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={index}
                                    href={link.url}
                                    className={`rounded px-3 py-1 text-sm ${
                                        link.active
                                            ? 'bg-primary-600 text-white'
                                            : 'border border-gray-200 text-gray-700 hover:bg-gray-50'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ) : null,
                        )}
                    </div>
                ) : null}
            </div>
        </AppLayout>
    );
}
