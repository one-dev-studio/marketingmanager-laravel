import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { router, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface AutomationRuleItem {
    id: number;
    name: string;
    is_active: boolean;
    trigger_conditions: unknown;
    actions: unknown;
}

export interface AutomationsIndexProps {
    organizationId: string;
    rules: AutomationRuleItem[];
}

export default function Index({ organizationId, rules }: AutomationsIndexProps) {
    const form = useForm({
        name: '',
        trigger: 'campaign.published',
        action: 'notify',
    });

    const submitCreate = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.automations.store', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => form.reset('name'),
        });
    };

    return (
        <AppLayout title="Automations">
            <div className="space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">Automation rules</h1>
                <p className="text-sm text-gray-600">
                    Simple trigger/action rules. Visual canvas lives under{' '}
                    <a
                        href={route('main.workflows.builder', { organizationId })}
                        className="text-primary-600 hover:underline"
                    >
                        Workflows
                    </a>
                    .
                </p>

                <form
                    onSubmit={submitCreate}
                    className="grid gap-2 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-4"
                >
                    <input
                        name="name"
                        required
                        placeholder="Rule name"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.name}
                        onChange={(e) => form.setData('name', e.target.value)}
                    />
                    <select
                        name="trigger"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.trigger}
                        onChange={(e) => form.setData('trigger', e.target.value)}
                    >
                        <option value="campaign.published">Campaign published</option>
                        <option value="email.sent">Email sent</option>
                        <option value="review.received">Review received</option>
                    </select>
                    <select
                        name="action"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.action}
                        onChange={(e) => form.setData('action', e.target.value)}
                    >
                        <option value="notify">Notify team</option>
                        <option value="create_task">Create task</option>
                        <option value="slack">Send Slack</option>
                    </select>
                    <Button type="submit" disabled={form.processing}>Create</Button>
                </form>

                <div className="rounded-lg border border-gray-200 bg-white">
                    {rules.length === 0 ? (
                        <p className="p-8 text-center text-gray-500">No rules yet.</p>
                    ) : (
                        rules.map((rule) => (
                            <div
                                key={rule.id}
                                className="flex items-center justify-between gap-4 border-b border-gray-100 p-4 last:border-b-0"
                            >
                                <div>
                                    <p className="font-medium text-gray-900">{rule.name}</p>
                                    <p className="text-xs text-gray-500">
                                        {JSON.stringify(rule.trigger_conditions)} → {JSON.stringify(rule.actions)}
                                    </p>
                                </div>
                                <div className="flex gap-2 text-sm">
                                    <button
                                        type="button"
                                        className="text-primary-600"
                                        onClick={() =>
                                            router.post(
                                                route('main.automations.test', {
                                                    organizationId,
                                                    automationRule: rule.id,
                                                }),
                                                {},
                                                { preserveScroll: true },
                                            )
                                        }
                                    >
                                        Test
                                    </button>
                                    <button
                                        type="button"
                                        className="text-primary-600"
                                        onClick={() =>
                                            router.post(
                                                route('main.automations.toggle', {
                                                    organizationId,
                                                    automationRule: rule.id,
                                                }),
                                                {},
                                                { preserveScroll: true },
                                            )
                                        }
                                    >
                                        {rule.is_active ? 'Pause' : 'Activate'}
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
