import { Button } from '@/components/ui/button';
import AgencyLayout from '@/Layouts/AgencyLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import { route } from 'ziggy-js';

type SettingsTab = 'profile' | 'branding' | 'defaults' | 'integrations' | 'notifications';

interface BrandingState {
    logo: string | null;
    primary_color: string;
    secondary_color: string;
    portal_enabled: boolean;
    portal_title: string;
    portal_welcome_text: string;
    portal_logo_url: string;
    portal_primary_color: string;
    portal_secondary_color: string;
}

export interface AgencySettingsIndexProps {
    agencyId: string;
    agencyName: string;
    branding: BrandingState;
    defaults: { timezone: string };
    integrations: { slack_webhook: string };
}

const TABS: SettingsTab[] = ['profile', 'branding', 'defaults', 'integrations', 'notifications'];

export default function Index({
    agencyId,
    agencyName,
    branding,
    defaults,
    integrations,
}: AgencySettingsIndexProps) {
    const [tab, setTab] = useState<SettingsTab>('profile');

    const profileForm = useForm({ name: agencyName });
    const brandingForm = useForm({
        logo: branding.logo ?? '',
        primary_color: branding.primary_color,
        secondary_color: branding.secondary_color,
        portal_enabled: branding.portal_enabled,
        portal_title: branding.portal_title,
        portal_welcome_text: branding.portal_welcome_text,
        portal_logo_url: branding.portal_logo_url,
        portal_primary_color: branding.portal_primary_color,
        portal_secondary_color: branding.portal_secondary_color,
    });
    const defaultsForm = useForm({ timezone: defaults.timezone });
    const integrationsForm = useForm({ slack_webhook: integrations.slack_webhook });
    const notificationsForm = useForm({ notify_email: false });

    const submitProfile = (event: FormEvent) => {
        event.preventDefault();
        profileForm.put(route('agency.settings.update-profile', { agency: agencyId }), { preserveScroll: true });
    };

    const submitBranding = (event: FormEvent) => {
        event.preventDefault();
        brandingForm.put(route('agency.settings.update-branding', { agency: agencyId }), { preserveScroll: true });
    };

    const submitDefaults = (event: FormEvent) => {
        event.preventDefault();
        defaultsForm.put(route('agency.settings.update-defaults', { agency: agencyId }), { preserveScroll: true });
    };

    const submitIntegrations = (event: FormEvent) => {
        event.preventDefault();
        integrationsForm.put(route('agency.settings.update-integrations', { agency: agencyId }), {
            preserveScroll: true,
        });
    };

    const submitNotifications = (event: FormEvent) => {
        event.preventDefault();
        notificationsForm.put(route('agency.settings.update-notifications', { agency: agencyId }), {
            preserveScroll: true,
        });
    };

    return (
        <AgencyLayout title="Settings">
            <div className="space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Agency settings</h1>
                <div className="flex flex-wrap gap-2">
                    {TABS.map((tabName) => (
                        <button
                            key={tabName}
                            type="button"
                            className={`rounded border px-3 py-1 text-sm capitalize ${
                                tab === tabName ? 'border-primary-600 bg-primary-50' : 'border-gray-200'
                            }`}
                            onClick={() => setTab(tabName)}
                        >
                            {tabName}
                        </button>
                    ))}
                </div>

                {tab === 'profile' && (
                    <form onSubmit={submitProfile} className="space-y-3 rounded-lg border border-gray-200 bg-white p-4">
                        <label className="block text-sm font-medium text-gray-700">Agency name</label>
                        <input
                            name="name"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={profileForm.data.name}
                            onChange={(e) => profileForm.setData('name', e.target.value)}
                        />
                        <Button type="submit" disabled={profileForm.processing}>Save profile</Button>
                    </form>
                )}

                {tab === 'branding' && (
                    <form onSubmit={submitBranding} className="space-y-4 rounded-lg border border-gray-200 bg-white p-4">
                        <input
                            name="logo"
                            placeholder="Logo URL"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={brandingForm.data.logo}
                            onChange={(e) => brandingForm.setData('logo', e.target.value)}
                        />
                        <input
                            name="primary_color"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={brandingForm.data.primary_color}
                            onChange={(e) => brandingForm.setData('primary_color', e.target.value)}
                        />
                        <input
                            name="secondary_color"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={brandingForm.data.secondary_color}
                            onChange={(e) => brandingForm.setData('secondary_color', e.target.value)}
                        />
                        <label className="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                checked={brandingForm.data.portal_enabled}
                                onChange={(e) => brandingForm.setData('portal_enabled', e.target.checked)}
                            />
                            Enable client portal
                        </label>
                        <input
                            name="portal_title"
                            placeholder="Portal title"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={brandingForm.data.portal_title}
                            onChange={(e) => brandingForm.setData('portal_title', e.target.value)}
                        />
                        <textarea
                            name="portal_welcome_text"
                            rows={3}
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={brandingForm.data.portal_welcome_text}
                            onChange={(e) => brandingForm.setData('portal_welcome_text', e.target.value)}
                        />
                        <Button type="submit" disabled={brandingForm.processing}>Save branding</Button>
                    </form>
                )}

                {tab === 'defaults' && (
                    <form onSubmit={submitDefaults} className="space-y-3 rounded-lg border border-gray-200 bg-white p-4">
                        <input
                            name="timezone"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={defaultsForm.data.timezone}
                            onChange={(e) => defaultsForm.setData('timezone', e.target.value)}
                        />
                        <Button type="submit" disabled={defaultsForm.processing}>Save defaults</Button>
                    </form>
                )}

                {tab === 'integrations' && (
                    <form
                        onSubmit={submitIntegrations}
                        className="space-y-3 rounded-lg border border-gray-200 bg-white p-4"
                    >
                        <input
                            name="slack_webhook"
                            placeholder="Slack webhook"
                            className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={integrationsForm.data.slack_webhook}
                            onChange={(e) => integrationsForm.setData('slack_webhook', e.target.value)}
                        />
                        <Button type="submit" disabled={integrationsForm.processing}>Save integrations</Button>
                    </form>
                )}

                {tab === 'notifications' && (
                    <form
                        onSubmit={submitNotifications}
                        className="space-y-3 rounded-lg border border-gray-200 bg-white p-4"
                    >
                        <label className="flex items-center gap-2 text-sm">
                            <input
                                type="checkbox"
                                checked={notificationsForm.data.notify_email}
                                onChange={(e) => notificationsForm.setData('notify_email', e.target.checked)}
                            />
                            Email
                        </label>
                        <Button type="submit" disabled={notificationsForm.processing}>Save notifications</Button>
                    </form>
                )}
            </div>
        </AgencyLayout>
    );
}
