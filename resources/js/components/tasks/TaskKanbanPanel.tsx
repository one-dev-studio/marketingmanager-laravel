import { Button } from '@/components/ui/button';
import type { TaskKanbanItem, TaskMember } from '@/types/tasks';
import axios from 'axios';
import { useCallback, useState } from 'react';
import { route } from 'ziggy-js';

const COLUMNS = [
    { key: 'todo', label: 'To Do' },
    { key: 'in_progress', label: 'In Progress' },
    { key: 'completed', label: 'Done' },
] as const;

export interface TaskKanbanPanelProps {
    organizationId: string;
    members: TaskMember[];
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

export function TaskKanbanPanel({ organizationId, members, initialTasks }: TaskKanbanPanelProps) {
    const [tasks, setTasks] = useState<TaskKanbanItem[]>(initialTasks);
    const [dragging, setDragging] = useState<TaskKanbanItem | null>(null);
    const [showForm, setShowForm] = useState(false);
    const [selected, setSelected] = useState<TaskKanbanItem | null>(null);
    const [comment, setComment] = useState('');
    const [form, setForm] = useState({
        title: '',
        description: '',
        due_date: '',
        assignee_id: '',
        status: 'todo',
    });

    const reload = useCallback(async () => {
        const { data } = await axios.get(route('main.tasks.index', { organizationId }), {
            headers: { Accept: 'application/json' },
        });
        const list = data.data?.data ?? data.data ?? [];
        setTasks(Array.isArray(list) ? list : []);
    }, [organizationId]);

    const drop = async (status: string) => {
        if (!dragging) {
            return;
        }
        await axios.post(
            route('main.tasks.status', { organizationId, task: dragging.id }),
            { status },
        );
        setTasks((prev) =>
            prev.map((task) => (task.id === dragging.id ? { ...task, status } : task)),
        );
        setDragging(null);
    };

    const openTask = (task: TaskKanbanItem) => {
        setSelected(task);
        setForm({
            title: task.title,
            description: task.description ?? '',
            due_date: task.due_date ?? '',
            assignee_id: task.assignee_id ? String(task.assignee_id) : '',
            status: task.status,
        });
        setShowForm(true);
    };

    const close = () => {
        setShowForm(false);
        setSelected(null);
    };

    const save = async () => {
        const payload = {
            ...form,
            assignee_id: form.assignee_id ? Number.parseInt(form.assignee_id, 10) : null,
        };
        if (selected) {
            await axios.put(route('main.tasks.update', { organizationId, task: selected.id }), payload);
        } else {
            await axios.post(route('main.tasks.store', { organizationId }), payload);
        }
        close();
        await reload();
    };

    const addComment = async () => {
        if (!selected || !comment.trim()) {
            return;
        }
        await axios.post(route('main.tasks.comments.store', { organizationId, task: selected.id }), {
            body: comment,
            comment,
        });
        setComment('');
        await reload();
    };

    return (
        <div>
            <div className="mb-4 flex justify-between">
                <h1 className="text-2xl font-semibold text-gray-900">Tasks</h1>
                <Button type="button" onClick={() => setShowForm(true)}>New task</Button>
            </div>
            <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                {COLUMNS.map((col) => (
                    <div
                        key={col.key}
                        className="min-h-[24rem] rounded-lg border border-gray-200 bg-gray-50 p-3"
                        onDragOver={(event) => event.preventDefault()}
                        onDrop={() => void drop(col.key === 'completed' ? 'completed' : col.key)}
                    >
                        <h2 className="mb-3 font-medium text-gray-900">{col.label}</h2>
                        {tasks
                            .filter((task) => matchesColumn(task.status, col.key))
                            .map((task) => (
                                <div
                                    key={task.id}
                                    draggable
                                    className="mb-2 cursor-move rounded border border-gray-200 bg-white p-3"
                                    onDragStart={() => setDragging(task)}
                                    onClick={() => openTask(task)}
                                >
                                    <p className="text-sm font-medium text-gray-900">{task.title}</p>
                                    <p className="text-xs text-gray-500">
                                        {task.due_date ?? 'No due date'} · {task.assignee?.name ?? 'Unassigned'}
                                    </p>
                                </div>
                            ))}
                    </div>
                ))}
            </div>

            {showForm ? (
                <div
                    className="fixed inset-0 flex items-center justify-center bg-black/40"
                    onClick={(event) => {
                        if (event.target === event.currentTarget) {
                            close();
                        }
                    }}
                >
                    <form
                        className="w-full max-w-lg space-y-3 rounded-lg bg-white p-6"
                        onSubmit={(event) => {
                            event.preventDefault();
                            void save();
                        }}
                    >
                        <input
                            required
                            value={form.title}
                            className="w-full rounded border border-gray-300 px-3 py-2"
                            placeholder="Title"
                            onChange={(event) => setForm((f) => ({ ...f, title: event.target.value }))}
                        />
                        <textarea
                            value={form.description}
                            className="w-full rounded border border-gray-300 px-3 py-2"
                            placeholder="Description"
                            onChange={(event) => setForm((f) => ({ ...f, description: event.target.value }))}
                        />
                        <input
                            type="datetime-local"
                            value={form.due_date}
                            className="w-full rounded border border-gray-300 px-3 py-2"
                            onChange={(event) => setForm((f) => ({ ...f, due_date: event.target.value }))}
                        />
                        <select
                            value={form.assignee_id}
                            className="w-full rounded border border-gray-300 px-3 py-2"
                            onChange={(event) => setForm((f) => ({ ...f, assignee_id: event.target.value }))}
                        >
                            <option value="">Assignee</option>
                            {members.map((member) => (
                                <option key={member.id} value={member.id}>{member.name}</option>
                            ))}
                        </select>
                        {selected ? (
                            <div className="max-h-40 space-y-2 overflow-y-auto text-sm">
                                <p className="font-medium">Comments</p>
                                {(selected.comments ?? []).map((c) => (
                                    <p key={c.id}>{c.user?.name}: {c.body ?? c.comment}</p>
                                ))}
                                <input
                                    value={comment}
                                    className="w-full rounded border border-gray-300 px-2 py-1"
                                    placeholder="Add comment"
                                    onChange={(event) => setComment(event.target.value)}
                                    onKeyDown={(event) => {
                                        if (event.key === 'Enter') {
                                            event.preventDefault();
                                            void addComment();
                                        }
                                    }}
                                />
                            </div>
                        ) : null}
                        <div className="flex gap-2">
                            <Button type="submit">Save</Button>
                            <button type="button" className="text-sm text-gray-600" onClick={close}>Close</button>
                        </div>
                    </form>
                </div>
            ) : null}
        </div>
    );
}
