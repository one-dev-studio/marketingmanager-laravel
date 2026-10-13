import { SurveyBuilderPanel } from '@/components/surveys/SurveyBuilderPanel';
import AppLayout from '@/Layouts/AppLayout';
import type { SurveyBuilderData } from '@/types/surveys';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface SurveysBuilderProps {
    organizationId: string;
    survey: SurveyBuilderData;
}

export default function Builder({ organizationId, survey }: SurveysBuilderProps) {
    return (
        <AppLayout title={`Survey builder — ${survey.title}`}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{survey.title}</h1>
                        <p className="text-sm text-gray-500">{survey.status}</p>
                    </div>
                    <Link
                        href={route('main.surveys.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>

                <SurveyBuilderPanel organizationId={organizationId} survey={survey} />

                <p className="text-sm text-gray-600">
                    Share:{' '}
                    <code className="rounded bg-gray-100 px-1 py-0.5 text-xs">{survey.public_url}</code>
                    {' · '}
                    Embed:{' '}
                    <code className="rounded bg-gray-100 px-1 py-0.5 text-xs">
                        {`<iframe src="${survey.public_url}"></iframe>`}
                    </code>
                </p>
            </div>
        </AppLayout>
    );
}
