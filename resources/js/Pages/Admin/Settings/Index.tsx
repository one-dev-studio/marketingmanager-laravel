import { Button } from '@/components/ui/button';
import AdminLayout from '@/Layouts/AdminLayout';
import { router, useForm } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface FeatureFlagRow {
    id: number;
    name: string;
    enabled: boolean;
}

export interface AdminSettingsIndexProps {
    settings: Record<string, unknown>;
    featureFlags: FeatureFlagRow[];
    maintenanceMode: boolean;
}

export default function Index({ settings, featureFlags, maintenanceMode }: AdminSettingsIndexProps) {
    const settingForm = useForm({ key: '', value: '' });
    const apiKeyForm = useForm({ service: '', key: '' });

    const settingEntries = Object.entries(settings ?? {});

    return (
        <AdminLayout title="Platform Settings">
            <div className="space-y-6">
                <div>
                    <h2 className="text-2xl font-bold text-gray-900">Platform Settings</h2>
                    <p className="mt-1 text-gray-600">Configure global platform settings and feature flags</p>
                </div>
                <div className="space-y-2 rounded-lg border border-gray-200 p-4">
                    <p className="text-sm text-gray-500">Maintenance Mode</p>
                    <p className="text-lg font-semibold text-gray-900">{maintenanceMode ? 'Enabled' : 'Disabled'}</p>
                    {maintenanceMode ? (
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => router.post(route('admin.settings.disable-maintenance'))}
                        >
                            Disable
                        </Button>
                    ) : (
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => router.post(route('admin.settings.enable-maintenance'))}
                        >
                            Enable
                        </Button>
                    )}
                </div>
                <form
                    className="space-y-2 rounded-lg border p-4"
                    onSubmit={(event) => {
                        event.preventDefault();
                        settingForm.post(route('admin.settings.update'));
                    }}
                >
                    <h3 className="font-semibold">Update setting</h3>
                    <input
                        name="key"
                        placeholder="key"
                        required
                        value={settingForm.data.key}
                        onChange={(event) => settingForm.setData('key', event.target.value)}
                        className="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                    <input
                        name="value"
                        placeholder="value"
                        required
                        value={settingForm.data.value}
                        onChange={(event) => settingForm.setData('value', event.target.value)}
                        className="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                    <Button type="submit" disabled={settingForm.processing}>Save</Button>
                </form>
                <form
                    className="space-y-2 rounded-lg border p-4"
                    onSubmit={(event) => {
                        event.preventDefault();
                        apiKeyForm.post(route('admin.settings.update-api-key'));
                    }}
                >
                    <h3 className="font-semibold">API key</h3>
                    <input
                        name="service"
                        placeholder="service"
                        required
                        value={apiKeyForm.data.service}
                        onChange={(event) => apiKeyForm.setData('service', event.target.value)}
                        className="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                    <input
                        name="key"
                        placeholder="value"
                        required
                        value={apiKeyForm.data.key}
                        onChange={(event) => apiKeyForm.setData('key', event.target.value)}
                        className="w-full rounded-md border border-gray-300 px-3 py-2"
                    />
                    <Button type="submit" disabled={apiKeyForm.processing}>Save key</Button>
                </form>
                <div className="rounded-lg border border-gray-200 p-4">
                    <h3 className="text-lg font-semibold text-gray-900">Global Settings</h3>
                    {settingEntries.length === 0 ? (
                        <p className="mt-2 text-sm text-gray-500">No global settings configured.</p>
                    ) : (
                        <dl className="mt-4 space-y-2">
                            {settingEntries.map(([key, value]) => (
                                <div key={key} className="flex justify-between text-sm">
                                    <dt className="text-gray-600">{key}</dt>
                                    <dd className="text-gray-900">{typeof value === 'object' ? JSON.stringify(value) : String(value)}</dd>
                                </div>
                            ))}
                        </dl>
                    )}
                </div>
                <div className="rounded-lg border border-gray-200 p-4">
                    <h3 className="text-lg font-semibold text-gray-900">Feature Flags</h3>
                    {featureFlags.length === 0 ? (
                        <p className="mt-2 text-sm text-gray-500">No feature flags configured.</p>
                    ) : (
                        <ul className="mt-4 space-y-2">
                            {featureFlags.map((flag) => (
                                <li key={flag.id} className="text-sm text-gray-600">
                                    {flag.name} · {flag.enabled ? 'Enabled' : 'Disabled'}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
