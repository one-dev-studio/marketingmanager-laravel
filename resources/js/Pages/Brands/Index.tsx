import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { BrandListItem } from '@/types/brands';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedBrands {
    data: BrandListItem[];
    links: PaginationLink[];
    meta?: {
        current_page: number;
        last_page: number;
    };
}

export interface BrandsIndexProps {
    organizationId: string;
    brands: PaginatedBrands;
    can: {
        create: boolean;
    };
}

function truncate(text: string | null, max: number): string {
    if (!text) {
        return '';
    }
    return text.length > max ? `${text.slice(0, max)}…` : text;
}

export default function Index({ organizationId, brands, can }: BrandsIndexProps) {
    const deleteBrand = (brandId: number) => {
        if (!window.confirm('Delete this brand? This cannot be undone.')) {
            return;
        }
        router.delete(route('main.brands.destroy', { organizationId, brand: brandId }));
    };

    const items = brands.data ?? [];

    return (
        <AppLayout title="Brands">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Brands</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Manage brand guidelines, tone of voice, and keywords
                        </p>
                    </div>
                    {can.create ? (
                        <div className="flex items-center gap-3">
                            <Link
                                href={route('main.brands.choose-name', { organizationId })}
                                className="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                            >
                                Choose name
                            </Link>
                            <Link href={route('main.brands.create', { organizationId })}>
                                <Button type="button">Add Brand</Button>
                            </Link>
                        </div>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No brands yet.</div>
                    ) : (
                        items.map((brand) => (
                            <div
                                key={brand.id}
                                className="flex items-start justify-between gap-4 border-b border-gray-100 p-4 last:border-b-0 hover:bg-gray-50"
                            >
                                <div className="min-w-0">
                                    <Link
                                        href={route('main.brands.show', {
                                            organizationId,
                                            brand: brand.id,
                                        })}
                                        className="font-medium text-gray-900 hover:text-primary-700"
                                    >
                                        {brand.name}
                                    </Link>
                                    <p className="mt-1 text-sm text-gray-500">
                                        {brand.status}
                                        {brand.summary ? ` · ${truncate(brand.summary, 120)}` : ''}
                                    </p>
                                    {brand.guidelines ? (
                                        <p className="mt-1 text-sm text-gray-400">
                                            {truncate(brand.guidelines, 160)}
                                        </p>
                                    ) : null}
                                </div>
                                <div className="flex shrink-0 items-center gap-3">
                                    {brand.can.update ? (
                                        <Link
                                            href={route('main.brands.edit', {
                                                organizationId,
                                                brand: brand.id,
                                            })}
                                            className="text-sm text-primary-600 hover:text-primary-800"
                                        >
                                            Edit
                                        </Link>
                                    ) : null}
                                    {brand.can.delete ? (
                                        <button
                                            type="button"
                                            onClick={() => deleteBrand(brand.id)}
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

                {brands.links?.length > 1 ? (
                    <nav className="flex flex-wrap gap-2" aria-label="Pagination">
                        {brands.links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url}
                                    className={`rounded border px-3 py-1 text-sm ${
                                        link.active
                                            ? 'border-primary-600 bg-primary-50 text-primary-800'
                                            : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ) : (
                                <span
                                    key={`${link.label}-${index}`}
                                    className="rounded border border-gray-200 px-3 py-1 text-sm text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </nav>
                ) : null}
            </div>
        </AppLayout>
    );
}
