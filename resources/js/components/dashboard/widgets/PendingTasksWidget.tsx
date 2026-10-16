import { WidgetLoading } from '@/components/dashboard/WidgetLoading';
import type { DashboardPendingTaskItem } from '@/types/dashboard';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { route } from 'ziggy-js';

function formatDueDate(value?: string | null): string {
    if (!value) {
        return 'No due date';
    }
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }
    return date.toLocaleDateString();
}

function priorityClass(priority?: string): string {
    const normalized = priority?.toLowerCase() ?? '';
    if (normalized === 'high') {
        return 'bg-red-100 text-red-800';
    }
    if (normalized === 'medium') {
        return 'bg-yellow-100 text-yellow-800';
    }
    return 'bg-gray-100 text-gray-800';
}

export interface PendingTasksWidgetProps {
    organizationId: string;
}

export function PendingTasksWidget({ organizationId }: PendingTasksWidgetProps) {
    const [tasks, setTasks] = useState<DashboardPendingTaskItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let cancelled = false;

        const load = async () => {
            try {
                setLoading(true);
                const response = await axios.get<{ data: DashboardPendingTaskItem[] }>(
                    route('main.dashboard.pending-tasks', { organizationId }),
                    { params: { limit: 10 } },
                );
                if (!cancelled) {
                    setTasks(response.data.data ?? []);
                }
            } catch {
                if (!cancelled) {
                    setTasks([]);
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

    if (tasks.length === 0) {
        return <p className="py-8 text-center text-gray-500">No pending tasks</p>;
    }

    return (
        <div className="max-h-96 space-y-2 overflow-y-auto">
            {tasks.map((task) => (
                <div
                    key={task.id}
                    className="flex items-center justify-between rounded-lg bg-gray-50 p-3 hover:bg-gray-100"
                >
                    <div className="min-w-0 flex-1">
                        <p className="truncate text-sm font-medium text-gray-900">{task.title}</p>
                        <p className="mt-1 text-xs text-gray-500">{formatDueDate(task.due_date)}</p>
                    </div>
                    {task.priority ? (
                        <span
                            className={`rounded-full px-2 py-1 text-xs font-medium ${priorityClass(task.priority)}`}
                        >
                            {task.priority}
                        </span>
                    ) : null}
                </div>
            ))}
        </div>
    );
}
