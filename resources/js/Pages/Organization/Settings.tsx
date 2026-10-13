import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import type { OrganizationSettingsForm } from '@/types/organization-settings';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { route } from 'ziggy-js';

const TABS = ['general', 'integrations', 'notifications', 'security'] as const;
type SettingsTab = (typeof TABS)[number];

export interface OrganizationSettingsProps {
    organizationId: string;
    organization: OrganizationSettingsForm;
}

export default function Settings({ organizationId, organization }: OrganizationSettingsProps) {
    const [tab, setTab] = useState<SettingsTab>('general');

    const form = useForm<OrganizationSettingsForm>(organization);

    const setSetting = (
        key: keyof OrganizationSettingsForm['settings'],
        value: boolean | string,
    ) => {
        form.setData('settings', {
            ...form.data.settings,
            [key]: value,
        });
    };

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.put(route('main.settings.update', { organizationId }));
    };

    return (
        <AppLayout title="Organization Settings">
            <div className="mx-auto max-w-3xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Organization settings</h1>
                    <p className="mt-1 text-sm text-gray-600">
                        General preferences, integrations, and security
                    </p>
                </div>

                <div className="flex flex-wrap gap-2">
                    {TABS.map((tabKey) => (
                        <button
                            key={tabKey}
                            type="button"
                            onClick={() => setTab(tabKey)}
                            className={`rounded-md border px-3 py-1 text-sm capitalize ${
                                tab === tabKey
                                    ? 'border-primary-600 bg-primary-600 text-white'
                                    : 'border-gray-300 text-gray-700 hover:bg-gray-50'
                            }`}
                        >
                            {tabKey}
                        </button>
                    ))}
                </div>

                <form onSubmit={submit} className="space-y-4">
                    <div className="rounded-lg border border-gray-200 bg-white p-6">
                        {tab === 'general' ? (
                            <div className="space-y-4">
                                <div>
                                    <Label htmlFor="name">Organization name</Label>
                                    <Input
                                        id="name"
                                        value={form.data.name}
                                        onChange={(e) => form.setData('name', e.target.value)}
                                        required
                                    />
                                    <InputError message={form.errors.name} />
                                </div>
                                <div>
                                    <Label htmlFor="timezone">Timezone</Label>
                                    <Input
                                        id="timezone"
                                        value={form.data.timezone}
                                        onChange={(e) => form.setData('timezone', e.target.value)}
                                        placeholder="UTC"
                                        required
                                    />
                                    <InputError message={form.errors.timezone} />
                                </div>
                                <div>
                                    <Label htmlFor="locale">Locale</Label>
                                    <Input
                                        id="locale"
                                        value={form.data.locale}
                                        onChange={(e) => form.setData('locale', e.target.value)}
                                        required
                                    />
                                    <InputError message={form.errors.locale} />
                                </div>
                            </div>
                        ) : null}

                        {tab === 'integrations' ? (
                            <div>
                                <Label htmlFor="slack_webhook">Slack webhook URL</Label>
                                <Input
                                    id="slack_webhook"
                                    value={form.data.settings.slack_webhook}
                                    onChange={(e) => setSetting('slack_webhook', e.target.value)}
                                    placeholder="https://hooks.slack.com/..."
                                />
                            </div>
                        ) : null}

                        {tab === 'notifications' ? (
                            <div className="space-y-3">
                                <label className="flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        checked={form.data.settings.notify_email}
                                        onChange={(e) => setSetting('notify_email', e.target.checked)}
                                    />
                                    Email notifications
                                </label>
                                <label className="flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        checked={form.data.settings.notify_in_app}
                                        onChange={(e) => setSetting('notify_in_app', e.target.checked)}
                                    />
                                    In-app notifications
                                </label>
                                <label className="flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        checked={form.data.settings.notify_push}
                                        onChange={(e) => setSetting('notify_push', e.target.checked)}
                                    />
                                    Push notifications
                                </label>
                            </div>
                        ) : null}

                        {tab === 'security' ? (
                            <label className="flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    checked={form.data.settings.require_2fa}
                                    onChange={(e) => setSetting('require_2fa', e.target.checked)}
                                />
                                Require two-factor authentication for members
                            </label>
                        ) : null}
                    </div>

                    <Button type="submit" disabled={form.processing}>Save settings</Button>
                </form>
            </div>
        </AppLayout>
    );
}
