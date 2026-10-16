import { WidgetLoading } from '@/components/dashboard/WidgetLoading';
import type { DashboardActivityItem } from '@/types/dashboard';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { route } from 'ziggy-js';

function formatTime(iso: string): string {
    const date = new Date(iso);
    if (Number.isNaN(date.getTime())) {
        return iso;
    }
    return date.toLocaleString();
}

export interface ActivityFeedWidgetProps {
    organizationId: string;
}

export function ActivityFeedWidget({ organizationId }: ActivityFeedWidgetProps) {
    const [activities, setActivities] = useState<DashboardActivityItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let cancelled = false;

        const load = async () => {
            try {
                setLoading(true);
                const response = await axios.get<{ data: DashboardActivityItem[] }>(
                    route('main.dashboard.activity-feed', { organizationId }),
                    { params: { limit: 10 } },
                );
                if (!cancelled) {
                    setActivities(response.data.data ?? []);
                }
            } catch {
                if (!cancelled) {
                    setActivities([]);
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

    if (activities.length === 0) {
        return <p className="py-8 text-center text-gray-500">No recent activity</p>;
    }

    return (
        <div className="max-h-96 space-y-3 overflow-y-auto">
            {activities.map((activity) => {
                const initial = activity.user?.name?.charAt(0).toUpperCase() ?? 'A';
                return (
                    <div key={activity.id} className="flex items-start space-x-3">
                        <div className="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-primary-100">
                            <span className="text-xs font-semibold text-primary-600">{initial}</span>
                        </div>
                        <div className="min-w-0 flex-1">
                            <p className="text-sm text-gray-900">
                                <span className="font-medium">
                                    {activity.user?.name ?? 'System'}
                                </span>{' '}
                                {activity.description}
                            </p>
                            <p className="mt-1 text-xs text-gray-500">
                                {formatTime(activity.created_at)}
                            </p>
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
