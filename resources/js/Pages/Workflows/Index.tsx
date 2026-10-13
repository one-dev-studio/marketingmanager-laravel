import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface WorkflowListItem {
    id: number;
    name: string;
    description: string | null;
    type: string;
    is_active: boolean;
    executions_count: number;
    creator_name: string | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedWorkflows {
    data: WorkflowListItem[];
    links: PaginationLink[];
}

export interface WorkflowsIndexProps {
    organizationId: string;
    workflows: PaginatedWorkflows;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, workflows, can }: WorkflowsIndexProps) {
    const items = workflows.data ?? [];

    return (
        <AppLayout title="Workflows">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Workflows</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Create and manage automated workflows for your marketing processes
                        </p>
                    </div>
                    {can.create ? (
                        <Link href={route('main.workflows.builder', { organizationId })}>
                            <Button type="button">Create workflow</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No workflows yet.</div>
                    ) : (
                        items.map((workflow) => (
                            <div
                                key={workflow.id}
                                className="flex flex-col gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div>
                                    <h2 className="font-medium text-gray-900">{workflow.name}</h2>
                                    <p className="text-sm text-gray-500">
                                        {workflow.type} · {workflow.is_active ? 'Active' : 'Inactive'} ·{' '}
                                        {workflow.executions_count} runs
                                    </p>
                                    {workflow.description ? (
                                        <p className="mt-1 text-sm text-gray-600">{workflow.description}</p>
                                    ) : null}
                                </div>
                                <Link
                                    href={route('main.workflows.builder', {
                                        organizationId,
                                        id: workflow.id,
                                    })}
                                    className="text-sm font-medium text-primary-700 hover:text-primary-800"
                                >
                                    Open builder
                                </Link>
                            </div>
                        ))
                    )}
                </div>

                {workflows.links?.length > 1 ? (
                    <nav className="flex flex-wrap gap-2">
                        {workflows.links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={`${link.label}-${index}`}
                                    href={link.url}
                                    className={`rounded px-3 py-1 text-sm ${
                                        link.active
                                            ? 'bg-primary-100 text-primary-800'
                                            : 'text-gray-600 hover:bg-gray-100'
                                    }`}
                                >
                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                </Link>
                            ) : (
                                <span
                                    key={`${link.label}-${index}`}
                                    className="rounded px-3 py-1 text-sm text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </nav>
                ) : null}
            </div>
        </AppLayout>
    );
}
