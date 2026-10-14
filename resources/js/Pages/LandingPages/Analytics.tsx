import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface AnalyticsRow {
    id: number;
    event: string | null;
    metric: string | null;
    created_at: string | null;
}

export interface LandingPagesAnalyticsProps {
    organizationId: string;
    page: {
        id: number;
        name: string;
    };
    analytics: AnalyticsRow[];
    variantsCount: number;
}

export default function Analytics({ organizationId, page, analytics, variantsCount }: LandingPagesAnalyticsProps) {
    return (
        <AppLayout title="Page analytics">
            <div className="space-y-4">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">{page.name} analytics</h1>
                    <Link
                        href={route('main.landing-pages.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to landing pages
                    </Link>
                </div>
                <p className="text-sm text-gray-500">
                    Events: {analytics.length} · Variants: {variantsCount}
                </p>
                <ul className="divide-y rounded-lg border border-gray-200 bg-white">
                    {analytics.length === 0 ? (
                        <li className="p-3 text-sm text-gray-500">No analytics events yet.</li>
                    ) : (
                        analytics.map((row) => (
                            <li key={row.id} className="p-3 text-sm">
                                {row.event ?? row.metric ?? 'hit'}
                                {row.created_at ? ` · ${row.created_at}` : ''}
                            </li>
                        ))
                    )}
                </ul>
            </div>
        </AppLayout>
    );
}
