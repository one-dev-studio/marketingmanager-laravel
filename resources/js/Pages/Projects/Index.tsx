import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { router, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface ProjectListItem {
    id: number;
    name: string;
    status: string;
    progress: number;
    member_names: string[];
    client_name: string | null;
}

interface ProjectTemplateOption {
    id: number;
    name: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedProjects {
    data: ProjectListItem[];
    links: PaginationLink[];
}

export interface ProjectsIndexProps {
    organizationId: string;
    projects: PaginatedProjects;
    templates: ProjectTemplateOption[];
}

const STATUS_OPTIONS = ['planning', 'in_progress', 'review', 'completed'] as const;

function formatStatus(status: string): string {
    return status.replace(/_/g, ' ');
}

export default function Index({ organizationId, projects, templates }: ProjectsIndexProps) {
    const createForm = useForm({ name: '', status: 'planning' });
    const items = projects.data ?? [];

    const submitCreate = (event: FormEvent) => {
        event.preventDefault();
        createForm.post(route('main.projects.store', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => createForm.reset('name'),
        });
    };

    const createFromTemplate = (templateId: number) => {
        router.post(
            route('main.projects.templates.create-project', {
                organizationId,
                projectTemplate: templateId,
            }),
            {},
            { preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Projects">
            <div className="space-y-4">
                <div className="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                    <h1 className="text-2xl font-semibold text-gray-900">Projects</h1>
                    <form onSubmit={submitCreate} className="flex flex-wrap gap-2">
                        <input
                            name="name"
                            required
                            placeholder="New project"
                            className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={createForm.data.name}
                            onChange={(e) => createForm.setData('name', e.target.value)}
                        />
                        <select
                            name="status"
                            className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={createForm.data.status}
                            onChange={(e) => createForm.setData('status', e.target.value)}
                        >
                            {STATUS_OPTIONS.map((status) => (
                                <option key={status} value={status}>{formatStatus(status)}</option>
                            ))}
                        </select>
                        <Button type="submit" disabled={createForm.processing}>Create</Button>
                    </form>
                </div>

                {templates.length > 0 && (
                    <div className="text-sm text-gray-700">
                        Templates:
                        {templates.map((template) => (
                            <button
                                key={template.id}
                                type="button"
                                className="ml-2 text-primary-700 hover:underline"
                                onClick={() => createFromTemplate(template.id)}
                            >
                                {template.name}
                            </button>
                        ))}
                    </div>
                )}

                <div className="grid gap-4 md:grid-cols-2">
                    {items.length === 0 ? (
                        <div className="col-span-2 rounded-lg border border-gray-200 bg-white p-8 text-center text-gray-500">
                            No projects.
                        </div>
                    ) : (
                        items.map((project) => (
                            <div key={project.id} className="rounded-lg border border-gray-200 bg-white p-4">
                                <h2 className="font-medium text-gray-900">{project.name}</h2>
                                <p className="text-sm capitalize text-gray-500">
                                    {formatStatus(project.status)} · {project.progress}%
                                </p>
                                <div className="mt-2 h-2 rounded bg-gray-100">
                                    <div
                                        className="h-2 rounded bg-primary-600"
                                        style={{ width: `${Math.min(100, Math.max(0, project.progress))}%` }}
                                    />
                                </div>
                                <p className="mt-2 text-xs text-gray-400">
                                    {project.member_names.join(', ') || '—'} · {project.client_name ?? '—'}
                                </p>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
