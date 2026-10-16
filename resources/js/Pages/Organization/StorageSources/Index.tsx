import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import type { StorageProviderRow, StorageQuota } from '@/types/organization-storage';
import { router, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { route } from 'ziggy-js';

export interface OrganizationStorageSourcesIndexProps {
    organizationId: string;
    providers: StorageProviderRow[];
    quota: StorageQuota;
}

export default function Index({ organizationId, providers, quota }: OrganizationStorageSourcesIndexProps) {
    const [connectingProvider, setConnectingProvider] = useState<string | null>(null);

    const connectForm = useForm({
        provider: '',
        access_token: '',
    });

    const startConnect = (provider: string) => {
        setConnectingProvider(provider);
        connectForm.setData({ provider, access_token: '' });
    };

    const submitConnect = (event: FormEvent) => {
        event.preventDefault();
        connectForm.post(route('main.storage-sources.connect', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => {
                setConnectingProvider(null);
                connectForm.reset();
            },
        });
    };

    const disconnect = (provider: string) => {
        if (!window.confirm('Disconnect this storage provider?')) {
            return;
        }
        router.delete(
            route('main.storage-sources.disconnect', { organizationId, provider }),
            { preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Storage sources">
            <div className="mx-auto max-w-3xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Storage sources</h1>
                    <p className="mt-1 text-sm text-gray-600">
                        Quota {quota.used_human} / {quota.limit_human}
                    </p>
                </div>

                {providers.map((row) => (
                    <div
                        key={row.provider}
                        className="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div>
                            <h2 className="font-medium text-gray-900">{row.label}</h2>
                            <p className="text-sm text-gray-500">
                                {row.is_connected ? 'Connected' : 'Not connected'}
                            </p>
                        </div>
                        {row.is_connected ? (
                            <button
                                type="button"
                                className="text-sm text-red-600 hover:text-red-800"
                                onClick={() => disconnect(row.provider)}
                            >
                                Disconnect
                            </button>
                        ) : connectingProvider === row.provider ? (
                            <form onSubmit={submitConnect} className="flex flex-wrap items-end gap-2">
                                <div className="space-y-1">
                                    <Label htmlFor={`token-${row.provider}`}>Access token / key</Label>
                                    <Input
                                        id={`token-${row.provider}`}
                                        required
                                        value={connectForm.data.access_token}
                                        onChange={(e) =>
                                            connectForm.setData('access_token', e.target.value)
                                        }
                                        className="text-sm"
                                    />
                                    <InputError message={connectForm.errors.access_token} />
                                </div>
                                <Button type="submit" disabled={connectForm.processing}>
                                    Connect
                                </Button>
                                <button
                                    type="button"
                                    className="text-sm text-gray-600"
                                    onClick={() => setConnectingProvider(null)}
                                >
                                    Cancel
                                </button>
                            </form>
                        ) : (
                            <button
                                type="button"
                                className="text-sm text-primary-700 hover:text-primary-900"
                                onClick={() => startConnect(row.provider)}
                            >
                                Connect
                            </button>
                        )}
                    </div>
                ))}
            </div>
        </AppLayout>
    );
}
