import { TaskKanbanPanel } from '@/components/tasks/TaskKanbanPanel';
import AppLayout from '@/Layouts/AppLayout';
import type { TaskKanbanItem, TaskMember, TaskTemplateSummary } from '@/types/tasks';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface TasksIndexProps {
    organizationId: string;
    members: TaskMember[];
    templates: TaskTemplateSummary[];
    tasks: TaskKanbanItem[];
}

export default function Index({ organizationId, members, templates, tasks }: TasksIndexProps) {
    const createFromTemplate = (templateId: number) => {
        router.post(
            route('main.tasks.templates.create-task', {
                organizationId,
                taskTemplate: templateId,
            }),
        );
    };

    return (
        <AppLayout title="Tasks">
            <div className="space-y-6">
                <TaskKanbanPanel
                    organizationId={organizationId}
                    members={members}
                    initialTasks={tasks}
                />
                {templates.length > 0 ? (
                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <h2 className="mb-2 font-medium text-gray-900">Templates</h2>
                        <div className="flex flex-wrap gap-2">
                            {templates.map((template) => (
                                <button
                                    key={template.id}
                                    type="button"
                                    className="text-sm text-primary-700 hover:text-primary-800"
                                    onClick={() => createFromTemplate(template.id)}
                                >
                                    Use {template.name}
                                </button>
                            ))}
                        </div>
                    </div>
                ) : null}
            </div>
        </AppLayout>
    );
}
