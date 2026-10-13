import { Button } from '@/components/ui/button';
import type { TaskKanbanItem } from '@/types/tasks';
import axios from 'axios';
import { useMemo, useState } from 'react';
import { route } from 'ziggy-js';

const COLUMNS = [
    { key: 'todo', label: 'To Do' },
    { key: 'in_progress', label: 'In Progress' },
    { key: 'completed', label: 'Done' },
] as const;

interface ClientOption {
    id: number;
    name: string;
}

export interface AgencyTaskKanbanProps {
    agencyId: string;
    clients: ClientOption[];
    initialTasks: TaskKanbanItem[];
}

function matchesColumn(status: string, columnKey: string): boolean {
    if (columnKey === 'in_progress') {
        return ['in_progress', 'review'].includes(status);
    }
    if (columnKey === 'todo') {
        return status === 'todo' || status === 'pending';
    }
    return status === columnKey;
}

export function AgencyTaskKanban({ agencyId, clients, initialTasks }: AgencyTaskKanbanProps) {
    const [clientFilter, setClientFilter] = useState('');
    const [tasks, setTasks] = useState(initialTasks);

    const filtered = useMemo(() => {
        if (!clientFilter) {
            return tasks;
        }
        return tasks.filter((task) => String(task.organization_id) === clientFilter);
    }, [clientFilter, tasks]);

    const reload = async () => {
        const { data } = await axios.get(route('agency.tasks', { agency: agencyId }), {
            headers: { Accept: 'application/json' },
            params: { organization_id: clientFilter || undefined },
        });
        const list = data.data?.data ?? data.data ?? [];
        setTasks(Array.isArray(list) ? list : []);
    };

    const drop = async (status: string, task: TaskKanbanItem) => {
        const orgId = task.organization_id;
        if (!orgId) {
            return;
        }
        await axios.post(route('main.tasks.status', { organizationId: orgId, task: task.id }), { status });
        setTasks((prev) => prev.map((item) => (item.id === task.id ? { ...item, status } : item)));
    };

    return (
        <div className="space-y-4">
            <div className="flex flex-wrap items-center justify-between gap-3">
                <h2 className="text-2xl font-semibold text-gray-900">Cross-client tasks</h2>
                <select
                    className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={clientFilter}
                    onChange={(e) => {
                        setClientFilter(e.target.value);
                        void reload();
                    }}
                >
                    <option value="">All clients</option>
                    {clients.map((client) => (
                        <option key={client.id} value={client.id}>{client.name}</option>
                    ))}
                </select>
            </div>
            <div className="grid gap-4 md:grid-cols-3">
                {COLUMNS.map((column) => (
                    <div key={column.key} className="min-h-96 rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <h3 className="mb-3 font-medium text-gray-900">{column.label}</h3>
                        {filtered
                            .filter((task) => matchesColumn(task.status, column.key))
                            .map((task) => (
                                <div
                                    key={task.id}
                                    draggable
                                    className="mb-2 cursor-move rounded-md border border-gray-200 bg-white p-3"
                                    onDragStart={() => {
                                        (window as unknown as { __agencyDragTask?: TaskKanbanItem }).__agencyDragTask = task;
                                    }}
                                    onDragOver={(e) => e.preventDefault()}
                                    onDrop={() => {
                                        const dragged = (window as unknown as { __agencyDragTask?: TaskKanbanItem })
                                            .__agencyDragTask;
                                        if (dragged) {
                                            void drop(column.key, dragged);
                                        }
                                    }}
                                >
                                    <p className="text-sm font-medium text-gray-900">{task.title}</p>
                                    <p className="text-xs text-gray-500">{task.due_date ?? 'No due date'}</p>
                                    {task.organization_name ? (
                                        <span className="mt-1 inline-block rounded bg-primary-50 px-1 text-xs text-primary-800">
                                            {task.organization_name}
                                        </span>
                                    ) : null}
                                </div>
                            ))}
                    </div>
                ))}
            </div>
            <Button type="button" variant="outline" onClick={() => void reload()}>Refresh</Button>
        </div>
    );
}
