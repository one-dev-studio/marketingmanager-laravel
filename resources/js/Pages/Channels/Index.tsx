import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { ChannelListItem } from '@/types/channels';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ChannelsIndexProps {
    organizationId: string;
    channels: ChannelListItem[];
    oauthPlatforms: string[];
    can: {
        create: boolean;
    };
}

function formatType(type: string): string {
    return type.replace(/_/g, ' ');
}

function oauthLabel(platform: string): string {
    return platform === 'twitter' ? 'X' : platform;
}

export default function Index({
    organizationId,
    channels,
    oauthPlatforms,
    can,
}: ChannelsIndexProps) {
    const testChannel = (channelId: number) => {
        router.post(
            route('main.social.channels.test', { organizationId, channel: channelId }),
        );
    };

    const deleteChannel = (channelId: number) => {
        if (!window.confirm('Delete this channel?')) {
            return;
        }
        router.delete(
            route('main.social.channels.destroy', { organizationId, channel: channelId }),
        );
    };

    const disconnectConnection = (connectionId: number) => {
        router.delete(
            route('main.social.connections.destroy', {
                organizationId,
                connection: connectionId,
            }),
        );
    };

    return (
        <AppLayout title="Channels">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Channels</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Connect social, email, ads, and influencer destinations
                        </p>
                    </div>
                    {can.create ? (
                        <Link
                            href={route('main.social.channels.create', { organizationId })}
                            className="inline-flex"
                        >
                            <Button type="button">Add Channel</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-4">
                    <h2 className="mb-3 text-sm font-medium text-gray-900">OAuth connect</h2>
                    <div className="flex flex-wrap gap-2">
                        {oauthPlatforms.map((platform) => (
                            <a
                                key={platform}
                                href={route('main.social.auth.redirect', {
                                    organizationId,
                                    platform,
                                })}
                                className="rounded-md border border-gray-300 px-3 py-1.5 text-sm capitalize hover:bg-gray-50"
                            >
                                Connect {oauthLabel(platform)}
                            </a>
                        ))}
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    {channels.length === 0 ? (
                        <p className="col-span-full text-center text-gray-500">No channels yet.</p>
                    ) : (
                        channels.map((channel) => (
                            <div
                                key={channel.id}
                                className="space-y-3 rounded-lg border border-gray-200 bg-white p-4"
                            >
                                <div className="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 className="font-medium text-gray-900">
                                            {channel.display_name}
                                        </h3>
                                        <p className="text-sm capitalize text-gray-500">
                                            {formatType(channel.type)}
                                            {channel.platform ? ` · ${channel.platform}` : ''}
                                        </p>
                                    </div>
                                    <span
                                        className={`rounded-full px-2 py-1 text-xs ${
                                            channel.connected
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-600'
                                        }`}
                                    >
                                        {channel.connection_status_label}
                                    </span>
                                </div>
                                {channel.type === 'influencer' ? (
                                    <p className="text-sm text-gray-600">
                                        Followers:{' '}
                                        {channel.follower_count != null
                                            ? channel.follower_count.toLocaleString()
                                            : '0'}
                                        · Engagement: {channel.engagement_rate ?? 0}%
                                    </p>
                                ) : null}
                                <div className="flex flex-wrap items-center gap-3 text-sm">
                                    <button
                                        type="button"
                                        onClick={() => testChannel(channel.id)}
                                        className="text-primary-600 hover:text-primary-800"
                                    >
                                        Test
                                    </button>
                                    {channel.can.update ? (
                                        <Link
                                            href={route('main.social.channels.edit', {
                                                organizationId,
                                                channel: channel.id,
                                            })}
                                            className="text-primary-600 hover:text-primary-800"
                                        >
                                            Edit
                                        </Link>
                                    ) : null}
                                    {channel.has_social_connection &&
                                    channel.social_connection_id ? (
                                        <button
                                            type="button"
                                            onClick={() =>
                                                disconnectConnection(channel.social_connection_id!)
                                            }
                                            className="text-gray-600 hover:text-gray-800"
                                        >
                                            Disconnect
                                        </button>
                                    ) : null}
                                    {channel.can.delete ? (
                                        <button
                                            type="button"
                                            onClick={() => deleteChannel(channel.id)}
                                            className="text-red-600 hover:text-red-800"
                                        >
                                            Delete
                                        </button>
                                    ) : null}
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
