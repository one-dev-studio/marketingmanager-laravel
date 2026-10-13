import { LandingPageBuilderPanel } from '@/components/landing-pages/LandingPageBuilderPanel';
import AppLayout from '@/Layouts/AppLayout';
import type { LandingPageBuilderData } from '@/types/landing-pages';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface LandingPagesBuilderProps {
    organizationId: string;
    page: LandingPageBuilderData;
}

export default function Builder({ organizationId, page }: LandingPagesBuilderProps) {
    const variantForm = useForm({
        name: '',
        traffic_percentage: 50,
    });

    const submitVariant = (event: FormEvent) => {
        event.preventDefault();
        variantForm.post(
            route('main.landing-pages.variants.store', {
                organizationId,
                landingPage: page.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => variantForm.reset(),
            },
        );
    };

    return (
        <AppLayout title={`Builder — ${page.name}`}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{page.name}</h1>
                        <p className="text-sm text-gray-500">/{page.slug} · {page.status}</p>
                    </div>
                    <Link
                        href={route('main.landing-pages.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>

                <LandingPageBuilderPanel organizationId={organizationId} page={page} />

                {page.can.update ? (
                    <form
                        onSubmit={submitVariant}
                        className="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-white p-4"
                    >
                        <div>
                            <label className="block text-xs font-medium text-gray-600" htmlFor="variant-name">
                                Variant name
                            </label>
                            <input
                                id="variant-name"
                                name="name"
                                required
                                value={variantForm.data.name}
                                className="mt-1 rounded-md border border-gray-300 px-2 py-1 text-sm"
                                onChange={(event) => variantForm.setData('name', event.target.value)}
                            />
                        </div>
                        <div>
                            <label
                                className="block text-xs font-medium text-gray-600"
                                htmlFor="variant-traffic"
                            >
                                Traffic %
                            </label>
                            <input
                                id="variant-traffic"
                                name="traffic_percentage"
                                type="number"
                                min={0}
                                max={100}
                                value={variantForm.data.traffic_percentage}
                                className="mt-1 w-24 rounded-md border border-gray-300 px-2 py-1 text-sm"
                                onChange={(event) =>
                                    variantForm.setData(
                                        'traffic_percentage',
                                        Number.parseInt(event.target.value, 10) || 0,
                                    )
                                }
                            />
                        </div>
                        <button
                            type="submit"
                            className="text-sm font-medium text-primary-700 hover:text-primary-800"
                            disabled={variantForm.processing}
                        >
                            Add variant split
                        </button>
                    </form>
                ) : null}
            </div>
        </AppLayout>
    );
}
