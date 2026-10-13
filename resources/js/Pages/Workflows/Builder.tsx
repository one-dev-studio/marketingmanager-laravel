import { WorkflowBuilderPanel, type WorkflowBuilderData } from '@/components/workflows/WorkflowBuilderPanel';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface WorkflowsBuilderProps {
    organizationId: string;
    workflow: WorkflowBuilderData;
}

export default function Builder({ organizationId, workflow }: WorkflowsBuilderProps) {
    const title = workflow.id ? `Edit — ${workflow.name}` : 'Workflow builder';

    return (
        <AppLayout title={title}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">{title}</h1>
                    <Link
                        href={route('main.workflows.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>
                <WorkflowBuilderPanel organizationId={organizationId} workflow={workflow} />
            </div>
        </AppLayout>
    );
}
