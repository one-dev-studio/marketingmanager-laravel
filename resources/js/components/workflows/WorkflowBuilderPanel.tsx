import { Button } from '@/components/ui/button';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

export interface WorkflowBuilderData {
    id: number | null;
    name: string;
    description: string;
    type: string;
    steps: unknown[];
    is_active: boolean;
    can: {
        update: boolean;
    };
}

export interface WorkflowBuilderPanelProps {
    organizationId: string;
    workflow: WorkflowBuilderData;
}

const WORKFLOW_TYPES = ['automation', 'approval', 'notification', 'custom'] as const;

export function WorkflowBuilderPanel({ organizationId, workflow }: WorkflowBuilderPanelProps) {
    const [name, setName] = useState(workflow.name);
    const [description, setDescription] = useState(workflow.description);
    const [type, setType] = useState(workflow.type);
    const [stepsJson, setStepsJson] = useState(() => JSON.stringify(workflow.steps ?? [], null, 2));
    const [isActive, setIsActive] = useState(workflow.is_active);
    const [workflowId, setWorkflowId] = useState<number | null>(workflow.id);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState<string | null>(null);
    const [testOutput, setTestOutput] = useState<string | null>(null);

    const save = async () => {
        if (!workflow.can.update) {
            return;
        }
        let steps: unknown[] = [];
        try {
            steps = JSON.parse(stepsJson) as unknown[];
            if (!Array.isArray(steps)) {
                setMessage('Steps must be a JSON array.');
                return;
            }
        } catch {
            setMessage('Steps JSON is invalid.');
            return;
        }

        setSaving(true);
        setMessage(null);
        const payload = {
            name,
            description,
            type,
            steps,
            is_active: isActive,
        };

        try {
            if (workflowId) {
                await axios.put(
                    route('main.workflows.update', { organizationId, workflow: workflowId }),
                    payload,
                );
                setMessage('Workflow saved.');
            } else {
                const { data } = await axios.post(
                    route('main.workflows.store', { organizationId }),
                    payload,
                );
                const id = data?.data?.id as number | undefined;
                if (id) {
                    setWorkflowId(id);
                }
                setMessage('Workflow created.');
            }
        } catch (error: unknown) {
            const errMessage =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Save failed.';
            setMessage(errMessage);
        } finally {
            setSaving(false);
        }
    };

    const runTest = async () => {
        if (!workflowId) {
            setTestOutput('Save the workflow before testing.');
            return;
        }
        setTestOutput(null);
        try {
            const { data } = await axios.post(
                route('main.workflows.execute', { organizationId, workflow: workflowId }),
                { input_data: {} },
            );
            setTestOutput(JSON.stringify(data.data ?? data, null, 2));
        } catch (error: unknown) {
            const errMessage =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Test run failed.';
            setTestOutput(errMessage);
        }
    };

    return (
        <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
            <div className="grid gap-4 sm:grid-cols-2">
                <label className="block text-sm">
                    <span className="font-medium text-gray-700">Name</span>
                    <input
                        type="text"
                        value={name}
                        className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        onChange={(event) => setName(event.target.value)}
                    />
                </label>
                <label className="block text-sm">
                    <span className="font-medium text-gray-700">Type</span>
                    <select
                        value={type}
                        className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        onChange={(event) => setType(event.target.value)}
                    >
                        {WORKFLOW_TYPES.map((option) => (
                            <option key={option} value={option}>{option}</option>
                        ))}
                    </select>
                </label>
            </div>
            <label className="block text-sm">
                <span className="font-medium text-gray-700">Description</span>
                <textarea
                    value={description}
                    rows={2}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setDescription(event.target.value)}
                />
            </label>
            <label className="flex items-center gap-2 text-sm text-gray-700">
                <input
                    type="checkbox"
                    checked={isActive}
                    onChange={(event) => setIsActive(event.target.checked)}
                />
                Active
            </label>
            <label className="block text-sm">
                <span className="font-medium text-gray-700">Steps (JSON array)</span>
                <textarea
                    value={stepsJson}
                    rows={8}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 font-mono text-xs"
                    onChange={(event) => setStepsJson(event.target.value)}
                />
            </label>
            <div className="flex flex-wrap gap-3">
                <Button type="button" disabled={saving || !workflow.can.update} onClick={() => void save()}>
                    {saving ? 'Saving…' : 'Save workflow'}
                </Button>
                <Button type="button" variant="outline" onClick={() => void runTest()}>
                    Run test
                </Button>
            </div>
            {message ? <p className="text-sm text-gray-600">{message}</p> : null}
            {testOutput ? (
                <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-xs">
                    {testOutput}
                </pre>
            ) : null}
        </div>
    );
}
