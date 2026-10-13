import {
    ChannelFormFields,
    channelFormDefaults,
    type ChannelFormData,
} from '@/components/channels/ChannelFormFields';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface ChannelsCreateProps {
    organizationId: string;
}

function buildPayload(data: ChannelFormData) {
    return {
        display_name: data.display_name,
        type: data.type,
        platform: data.platform || null,
        status: data.status,
        settings: data.settings.api_key ? { api_key: data.settings.api_key } : {},
        follower_count: data.follower_count ? Number(data.follower_count) : undefined,
        engagement_rate: data.engagement_rate ? Number(data.engagement_rate) : undefined,
        handle: data.handle || null,
    };
}

export default function Create({ organizationId }: ChannelsCreateProps) {
    const form = useForm<ChannelFormData>(channelFormDefaults(null));

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.transform(() => buildPayload(form.data));
        form.post(route('main.social.channels.store', { organizationId }));
    };

    return (
        <AppLayout title="Add Channel">
            <div className="mx-auto max-w-2xl space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Add Channel</h1>
                <form
                    onSubmit={submit}
                    className="space-y-4 rounded-lg border border-gray-200 bg-white p-6"
                >
                    <ChannelFormFields
                        data={form.data}
                        errors={form.errors}
                        channel={null}
                        setData={(key, value) => form.setData(key, value)}
                        setSettingsApiKey={(value) =>
                            form.setData('settings', { ...form.data.settings, api_key: value })
                        }
                    />
                    <div className="flex gap-3 pt-2">
                        <Button type="submit" disabled={form.processing}>Save</Button>
                        <Link
                            href={route('main.social.channels.index', { organizationId })}
                            className="rounded-md border border-gray-300 px-4 py-2 text-sm hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
