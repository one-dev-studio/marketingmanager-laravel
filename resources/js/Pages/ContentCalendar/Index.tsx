import AppLayout from '@/Layouts/AppLayout';
import type { CalendarEventItem } from '@/types/content-workflow';
import { Link, router } from '@inertiajs/react';
import { useMemo } from 'react';
import { route } from 'ziggy-js';

export interface ContentCalendarIndexProps {
    organizationId: string;
    range: {
        start: string;
        end: string;
    };
    events: CalendarEventItem[];
}

function formatDayLabel(isoDate: string): string {
    return new Date(isoDate).toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'short',
        day: 'numeric',
    });
}

function formatTime(isoDate: string): string {
    return new Date(isoDate).toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
    });
}

export default function Index({ organizationId, range, events }: ContentCalendarIndexProps) {
    const grouped = useMemo(() => {
        const map = new Map<string, CalendarEventItem[]>();
        events.forEach((event) => {
            const key = new Date(event.start).toDateString();
            const bucket = map.get(key) ?? [];
            bucket.push(event);
            map.set(key, bucket);
        });
        return Array.from(map.entries()).sort(
            (a, b) => new Date(a[0]).getTime() - new Date(b[0]).getTime(),
        );
    }, [events]);

    const applyRange = (start: string, end: string) => {
        router.get(
            route('main.content-calendar.index', { organizationId }),
            { start, end },
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Content Calendar">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Content Calendar</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            {range.start} – {range.end}
                        </p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <button
                            type="button"
                            className="rounded-md border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50"
                            onClick={() => {
                                const start = new Date();
                                start.setDate(1);
                                const end = new Date(start.getFullYear(), start.getMonth() + 1, 0);
                                applyRange(start.toISOString().slice(0, 10), end.toISOString().slice(0, 10));
                            }}
                        >
                            This month
                        </button>
                        <Link
                            href={route('main.content-approvals.index', { organizationId })}
                            className="rounded-md border border-primary-200 bg-primary-50 px-3 py-1.5 text-sm font-medium text-primary-700 hover:bg-primary-100"
                        >
                            Content approvals
                        </Link>
                    </div>
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {grouped.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">
                            No scheduled content in this range.
                        </div>
                    ) : (
                        <ul className="divide-y divide-gray-100">
                            {grouped.map(([dayKey, dayEvents]) => (
                                <li key={dayKey} className="p-4">
                                    <h2 className="mb-3 text-sm font-semibold text-gray-900">
                                        {formatDayLabel(dayEvents[0]?.start ?? dayKey)}
                                    </h2>
                                    <ul className="space-y-2">
                                        {dayEvents.map((event) => (
                                            <li
                                                key={String(event.id)}
                                                className="flex flex-col gap-1 rounded-md border border-gray-100 bg-gray-50 p-3 sm:flex-row sm:items-center sm:justify-between"
                                            >
                                                <div>
                                                    <p className="font-medium text-gray-900">{event.title}</p>
                                                    {event.description ? (
                                                        <p className="text-sm text-gray-600">{event.description}</p>
                                                    ) : null}
                                                    <p className="text-xs uppercase tracking-wide text-gray-500">
                                                        {event.type}
                                                        {event.status ? ` · ${event.status}` : ''}
                                                    </p>
                                                </div>
                                                <span className="text-sm text-gray-500">
                                                    {formatTime(event.start)}
                                                </span>
                                            </li>
                                        ))}
                                    </ul>
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
