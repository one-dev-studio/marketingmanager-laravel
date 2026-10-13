import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { LandingPageListItem } from '@/types/landing-pages';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedLandingPages {
    data: LandingPageListItem[];
    links: PaginationLink[];
}

export interface LandingPagesIndexProps {
    organizationId: string;
    landingPages: PaginatedLandingPages;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, landingPages, can }: LandingPagesIndexProps) {
    const items = landingPages.data ?? [];

    const publish = (pageId: number) => {
        router.post(
            route('main.landing-pages.publish', { organizationId, landingPage: pageId }),
            {},
            { preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Landing Pages">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Landing Pages</h1>
                        <p className="mt-1 text-sm text-gray-600">Build, preview, and publish</p>
                    </div>
                    {can.create ? (
                        <Link href={route('main.landing-pages.create', { organizationId })}>
                            <Button type="button">Create</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No landing pages yet.</div>
                    ) : (
                        items.map((page) => (
                            <div
                                key={page.id}
                                className="flex flex-col gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div>
                                    <h2 className="font-medium text-gray-900">{page.name}</h2>
                                    <p className="text-sm text-gray-500">
                                        {page.status} · /{page.slug}
                                    </p>
                                </div>
                                <div className="flex flex-wrap gap-3 text-sm">
                                    <Link
                                        href={route('main.landing-pages.builder', {
                                            organizationId,
                                            landingPage: page.id,
                                        })}
                                        className="font-medium text-primary-700 hover:text-primary-800"
                                    >
                                        Builder
                                    </Link>
                                    <a
                                        href={route('main.landing-pages.preview', {
                                            organizationId,
                                            landingPage: page.id,
                                        })}
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Preview
                                    </a>
                                    <a
                                        href={route('main.landing-pages.analytics', {
                                            organizationId,
                                            landingPage: page.id,
                                        })}
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Analytics
                                    </a>
                                    {page.can.update ? (
                                        <button
                                            type="button"
                                            className="text-primary-700 hover:text-primary-800"
                                            onClick={() => publish(page.id)}
                                        >
                                            Publish
                                        </button>
                                    ) : null}
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {landingPages.links?.length ? (
                    <div className="flex flex-wrap gap-2 text-sm">
                        {landingPages.links.map((link) =>
                            link.url ? (
                                <Link
                                    key={link.label}
                                    href={link.url}
                                    className={
                                        link.active
                                            ? 'font-medium text-primary-700'
                                            : 'text-gray-600 hover:text-gray-900'
                                    }
                                    preserveScroll
                                >
                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                </Link>
                            ) : (
                                <span
                                    key={link.label}
                                    className="text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </div>
                ) : null}
            </div>
        </AppLayout>
    );
}
