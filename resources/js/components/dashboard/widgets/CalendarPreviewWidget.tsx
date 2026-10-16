import { WidgetLoading } from '@/components/dashboard/WidgetLoading';
import type { DashboardCalendarEventItem } from '@/types/dashboard';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { route } from 'ziggy-js';

function eventColor(type?: string): string {
    switch (type) {
        case 'campaign':
            return 'bg-blue-500';
        case 'task':
            return 'bg-amber-500';
        case 'content':
            return 'bg-green-500';
        default:
            return 'bg-gray-400';
    }
}

function formatEventTime(iso: string): string {
    const date = new Date(iso);
    if (Number.isNaN(date.getTime())) {
        return iso;
    }
    return date.toLocaleString();
}

export interface CalendarPreviewWidgetProps {
    organizationId: string;
}

export function CalendarPreviewWidget({ organizationId }: CalendarPreviewWidgetProps) {
    const [events, setEvents] = useState<DashboardCalendarEventItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let cancelled = false;

        const load = async () => {
            try {
                setLoading(true);
                const response = await axios.get<{ data: DashboardCalendarEventItem[] }>(
                    route('main.dashboard.content-calendar', { organizationId }),
                    { params: { days: 7 } },
                );
                if (!cancelled) {
                    setEvents(response.data.data ?? []);
                }
            } catch {
                if (!cancelled) {
                    setEvents([]);
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        };

        void load();

        return () => {
            cancelled = true;
        };
    }, [organizationId]);

    if (loading) {
        return <WidgetLoading />;
    }

    if (events.length === 0) {
        return <p className="py-8 text-center text-gray-500">No upcoming events</p>;
    }

    return (
        <div className="max-h-96 space-y-2 overflow-y-auto">
            {events.map((event) => (
                <div
                    key={event.id}
                    className="flex items-center space-x-3 rounded-lg p-2 hover:bg-gray-50"
                >
                    <div className={`h-2 w-2 flex-shrink-0 rounded-full ${eventColor(event.type)}`} />
                    <div className="min-w-0 flex-1">
                        <p className="truncate text-sm font-medium text-gray-900">{event.title}</p>
                        <p className="text-xs text-gray-500">{formatEventTime(event.start)}</p>
                    </div>
                </div>
            ))}
        </div>
    );
}
