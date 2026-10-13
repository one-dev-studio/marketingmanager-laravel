import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface EmailCampaignListItem {
    id: number;
    name: string;
    subject: string;
    status: string;
    scheduled_at: string | null;
    sent_at: string | null;
    opened_count: number;
    clicked_count: number;
    show_href: string;
}

interface PaginatedCampaigns {
    data: EmailCampaignListItem[];
}

export interface EmailCampaignsIndexProps {
    organizationId: string;
    campaigns: PaginatedCampaigns;
}

export default function Index({ organizationId, campaigns }: EmailCampaignsIndexProps) {
    const items = campaigns.data ?? [];

    return (
        <AppLayout title="Email Campaigns">
            <div className="space-y-4">
                <div className="flex flex-wrap items-center justify-between gap-3">
                    <h1 className="text-2xl font-semibold text-gray-900">Email campaigns</h1>
                    <div className="flex gap-2">
                        <Button asChild variant="outline">
                            <Link href={route('main.email-marketing.templates.index', { organizationId })}>Templates</Link>
                        </Button>
                        <Button asChild>
                            <Link href={route('main.email-marketing.campaigns.create', { organizationId })}>Create</Link>
                        </Button>
                    </div>
                </div>
                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No campaigns.</div>
                    ) : (
                        items.map((campaign) => (
                            <div key={campaign.id} className="flex justify-between border-b border-gray-100 p-4 last:border-b-0">
                                <div>
                                    <Link href={campaign.show_href} className="font-medium text-gray-900 hover:text-primary-700">
                                        {campaign.name}
                                    </Link>
                                    <p className="text-sm text-gray-500">
                                        {campaign.subject} · {campaign.status} · {campaign.scheduled_at ?? campaign.sent_at ?? '—'}
                                    </p>
                                    <p className="text-xs text-gray-400">
                                        Opens {campaign.opened_count} · Clicks {campaign.clicked_count}
                                    </p>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
