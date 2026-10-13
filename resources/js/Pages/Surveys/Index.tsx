import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { SurveyListItem } from '@/types/surveys';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedSurveys {
    data: SurveyListItem[];
    links: PaginationLink[];
}

export interface SurveysIndexProps {
    organizationId: string;
    surveys: PaginatedSurveys;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, surveys, can }: SurveysIndexProps) {
    const items = surveys.data ?? [];

    return (
        <AppLayout title="Surveys">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Surveys</h1>
                        <p className="mt-1 text-sm text-gray-600">Build, distribute, and analyze</p>
                    </div>
                    {can.create ? (
                        <Link href={route('main.surveys.create', { organizationId })}>
                            <Button type="button">Create</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No surveys yet.</div>
                    ) : (
                        items.map((survey) => (
                            <div
                                key={survey.id}
                                className="flex flex-col gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div>
                                    <h2 className="font-medium text-gray-900">{survey.title}</h2>
                                    <p className="text-sm text-gray-500">
                                        {survey.status} · {survey.response_count} responses
                                    </p>
                                </div>
                                <div className="flex flex-wrap gap-3 text-sm">
                                    <Link
                                        href={route('main.surveys.builder', {
                                            organizationId,
                                            survey: survey.id,
                                        })}
                                        className="font-medium text-primary-700 hover:text-primary-800"
                                    >
                                        Builder
                                    </Link>
                                    <a
                                        href={route('main.surveys.analytics', {
                                            organizationId,
                                            survey: survey.id,
                                        })}
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Analytics
                                    </a>
                                    <a
                                        href={route('public.survey', { survey: survey.id })}
                                        className="text-gray-700 hover:text-gray-900"
                                        target="_blank"
                                        rel="noreferrer"
                                    >
                                        Public link
                                    </a>
                                    <a
                                        href={route('main.surveys.export', {
                                            organizationId,
                                            survey: survey.id,
                                        })}
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Export
                                    </a>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {surveys.links?.length ? (
                    <div className="flex flex-wrap gap-2 text-sm">
                        {surveys.links.map((link) =>
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
