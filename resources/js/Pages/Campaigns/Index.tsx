import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { CampaignListItem } from '@/types/campaigns';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedCampaigns {
    data: CampaignListItem[];
    links: PaginationLink[];
    meta?: {
        current_page: number;
        last_page: number;
    };
}

export interface CampaignsIndexProps {
    organizationId: string;
    campaigns: PaginatedCampaigns;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, campaigns, can }: CampaignsIndexProps) {
    const items = campaigns.data ?? [];

    return (
        <AppLayout title="Campaigns">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Campaigns</h1>
                        <p className="mt-1 text-sm text-gray-600">Manage your marketing campaigns</p>
                    </div>
                    {can.create ? (
                        <Link
                            href={route('main.campaigns.create', { organizationId })}
                            className="inline-flex"
                        >
                            <Button type="button">Create Campaign</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No campaigns yet.</div>
                    ) : (
                        <ul className="divide-y divide-gray-100">
                            {items.map((campaign) => (
                                <li
                                    key={campaign.id}
                                    className="flex items-center justify-between gap-4 p-4 hover:bg-gray-50"
                                >
                                    <div>
                                        <h2 className="font-medium text-gray-900">{campaign.name}</h2>
                                        <p className="text-sm text-gray-500">
                                            {campaign.status}
                                            {campaign.brand_name ? ` · ${campaign.brand_name}` : ' · No brand'}
                                        </p>
                                    </div>
                                    <Link
                                        href={campaign.show_href}
                                        className="text-sm font-medium text-primary-600 hover:text-primary-800"
                                    >
                                        View
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>

                {campaigns.links && campaigns.links.length > 1 ? (
                    <nav className="flex flex-wrap gap-2" aria-label="Pagination">
                        {campaigns.links.map((link) =>
                            link.url ? (
                                <Link
                                    key={`${link.label}-${link.url}`}
                                    href={link.url}
                                    className={`rounded border px-3 py-1 text-sm ${
                                        link.active
                                            ? 'border-primary-600 bg-primary-50 text-primary-700'
                                            : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                                    }`}
                                    preserveScroll
                                >
                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                </Link>
                            ) : (
                                <span
                                    key={link.label}
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
