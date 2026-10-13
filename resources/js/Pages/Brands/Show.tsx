import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { BrandDetail } from '@/types/brands';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface BrandsShowProps {
    organizationId: string;
    brand: BrandDetail;
}

function joinKeywords(words: string[]): string {
    return words.length > 0 ? words.join(', ') : '—';
}

export default function Show({ organizationId, brand }: BrandsShowProps) {
    const deleteBrand = () => {
        if (!window.confirm('Delete this brand? This cannot be undone.')) {
            return;
        }
        router.delete(route('main.brands.destroy', { organizationId, brand: brand.id }));
    };

    return (
        <AppLayout title={brand.name}>
            <div className="mx-auto max-w-3xl space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{brand.name}</h1>
                        <p className="mt-1 text-sm text-gray-600">{brand.status}</p>
                    </div>
                    <div className="flex items-center gap-3">
                        {brand.can.update ? (
                            <Link
                                href={route('main.brands.edit', {
                                    organizationId,
                                    brand: brand.id,
                                })}
                            >
                                <Button type="button">Edit</Button>
                            </Link>
                        ) : null}
                        {brand.can.delete ? (
                            <button
                                type="button"
                                onClick={deleteBrand}
                                className="text-sm text-red-600 hover:text-red-800"
                            >
                                Delete
                            </button>
                        ) : null}
                    </div>
                </div>

                <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Summary</h2>
                        <p className="mt-1 whitespace-pre-wrap text-gray-900">
                            {brand.summary || '—'}
                        </p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Guidelines</h2>
                        <p className="mt-1 whitespace-pre-wrap text-gray-900">
                            {brand.guidelines || '—'}
                        </p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Tone of voice</h2>
                        <p className="mt-1 text-gray-900">{brand.tone_of_voice || '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Audience</h2>
                        <p className="mt-1 whitespace-pre-wrap text-gray-900">
                            {brand.audience || '—'}
                        </p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Keywords to use</h2>
                        <p className="mt-1 text-gray-900">{joinKeywords(brand.keywords)}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Keywords to avoid</h2>
                        <p className="mt-1 text-gray-900">{joinKeywords(brand.avoid_keywords)}</p>
                    </div>
                </div>

                <Link
                    href={route('main.brands.index', { organizationId })}
                    className="text-sm text-primary-600 hover:text-primary-800"
                >
                    Back to brands
                </Link>
            </div>
        </AppLayout>
    );
}
