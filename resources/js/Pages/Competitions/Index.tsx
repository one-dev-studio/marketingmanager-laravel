import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface ContestListItem {
    id: number;
    name: string;
    status: string;
    entries_count: number;
    campaign_name: string | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedContests {
    data: ContestListItem[];
    links: PaginationLink[];
}

export interface CompetitionsIndexProps {
    organizationId: string;
    contests: PaginatedContests;
}

export default function Index({ organizationId, contests }: CompetitionsIndexProps) {
    const items = contests.data ?? [];

    return (
        <AppLayout title="Competitions">
            <div className="space-y-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">Competitions</h1>
                    <Button asChild>
                        <Link href={route('main.competitions.create', { organizationId })}>Create</Link>
                    </Button>
                </div>
                <div className="rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <p className="p-8 text-center text-gray-500">No competitions.</p>
                    ) : (
                        items.map((contest) => (
                            <Link
                                key={contest.id}
                                href={route('main.competitions.show', {
                                    organizationId,
                                    contest: contest.id,
                                })}
                                className="block border-b border-gray-100 p-4 text-sm last:border-b-0 hover:bg-gray-50"
                            >
                                {contest.name} · {contest.status} · {contest.entries_count} entries
                                {contest.campaign_name ? ` · ${contest.campaign_name}` : ''}
                            </Link>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
