import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface SurveySummary {
    id: number;
    title: string;
}

export interface SurveysAnalyticsProps {
    organizationId: string;
    survey: SurveySummary;
    analytics: Record<string, unknown>;
}

export default function Analytics({ organizationId, survey, analytics }: SurveysAnalyticsProps) {
    return (
        <AppLayout title={`Survey analytics — ${survey.title}`}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">{survey.title}</h1>
                    <Link
                        href={route('main.surveys.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to surveys
                    </Link>
                </div>
                <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-white p-4 text-sm">
                    {JSON.stringify(analytics, null, 2)}
                </pre>
            </div>
        </AppLayout>
    );
}
