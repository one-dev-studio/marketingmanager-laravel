import AgencyLayout from '@/Layouts/AgencyLayout';
import { router } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import { route } from 'ziggy-js';

interface ClientOption {
    id: number;
    name: string;
}

interface CalendarPost {
    id: number;
    organization_id: number;
    organization_name: string | null;
    title: string;
    scheduled_at: string;
}

interface CalendarCampaign {
    id: number;
    organization_id: number;
    organization_name: string | null;
    name: string;
    start_date: string;
}

export interface AgencyCalendarIndexProps {
    agencyId: string;
    clients: ClientOption[];
    scheduledPosts: CalendarPost[];
    campaigns: CalendarCampaign[];
    startDate: string;
    endDate: string;
}

export default function Index({
    agencyId,
    clients,
    scheduledPosts,
    campaigns,
    startDate,
    endDate,
}: AgencyCalendarIndexProps) {
    const [filter, setFilter] = useState('');

    const items = useMemo(() => {
        const posts = scheduledPosts.map((post) => ({
            key: `post-${post.id}`,
            organization_id: post.organization_id,
            organization_name: post.organization_name,
            title: post.title,
            when: post.scheduled_at,
            kind: 'Scheduled post',
        }));
        const launches = campaigns.map((campaign) => ({
            key: `campaign-${campaign.id}`,
            organization_id: campaign.organization_id,
            organization_name: campaign.organization_name,
            title: campaign.name,
            when: campaign.start_date,
            kind: 'Launch',
        }));
        return [...posts, ...launches].filter((item) => !filter || String(item.organization_id) === filter);
    }, [campaigns, filter, scheduledPosts]);

    return (
        <AgencyLayout title="Calendar">
            <div className="space-y-4">
                <div className="flex flex-wrap items-center justify-between gap-3">
                    <h1 className="text-2xl font-semibold text-gray-900">Aggregated calendar</h1>
                    <select
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={filter}
                        onChange={(e) => setFilter(e.target.value)}
                    >
                        <option value="">All clients</option>
                        {clients.map((client) => (
                            <option key={client.id} value={client.id}>{client.name}</option>
                        ))}
                    </select>
                </div>
                <p className="text-sm text-gray-600">{startDate} — {endDate}</p>
                <div className="grid gap-4 md:grid-cols-2">
                    {items.length === 0 ? (
                        <p className="text-sm text-gray-500">No scheduled items in this range.</p>
                    ) : (
                        items.map((item) => (
                            <div
                                key={item.key}
                                className="rounded-lg border border-gray-200 bg-white p-4"
                                style={{ borderLeftWidth: 4, borderLeftColor: `hsl(${(item.organization_id * 40) % 360} 70% 45%)` }}
                            >
                                <p className="text-xs text-gray-500">{item.organization_name ?? 'Client'} · {item.kind}</p>
                                <h2 className="font-medium text-gray-900">{item.title}</h2>
                                <p className="text-sm text-gray-600">{item.when}</p>
                            </div>
                        ))
                    )}
                </div>
                <button
                    type="button"
                    className="text-sm text-primary-700"
                    onClick={() =>
                        router.get(route('agency.calendar', { agency: agencyId }), { start: startDate, end: endDate })
                    }
                >
                    Reload range
                </button>
            </div>
        </AgencyLayout>
    );
}
