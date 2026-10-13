import { InputError } from '@/components/InputError';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    CHANNEL_STATUSES,
    CHANNEL_TYPES,
    type ChannelFormChannel,
    type ChannelType,
} from '@/types/channels';

export interface ChannelFormData {
    display_name: string;
    type: ChannelType;
    platform: string;
    status: string;
    follower_count: string;
    engagement_rate: string;
    handle: string;
    settings: {
        api_key: string;
    };
}

interface ChannelFormFieldsProps {
    data: ChannelFormData;
    errors: Record<string, string | undefined>;
    channel: ChannelFormChannel | null;
    setData: (key: keyof ChannelFormData, value: ChannelFormData[keyof ChannelFormData]) => void;
    setSettingsApiKey: (value: string) => void;
}

export function channelFormDefaults(channel: ChannelFormChannel | null): ChannelFormData {
    return {
        display_name: channel?.display_name ?? '',
        type: channel?.type ?? 'social',
        platform: channel?.platform ?? '',
        status: channel?.status ?? 'active',
        follower_count: channel?.follower_count != null ? String(channel.follower_count) : '',
        engagement_rate: channel?.engagement_rate != null ? String(channel.engagement_rate) : '',
        handle: channel?.handle ?? '',
        settings: {
            api_key: channel?.api_key ?? '',
        },
    };
}

export function ChannelFormFields({
    data,
    errors,
    channel,
    setData,
    setSettingsApiKey,
}: ChannelFormFieldsProps) {
    const typeLocked = channel !== null;

    return (
        <div className="space-y-4">
            <div>
                <Label htmlFor="display_name">Name</Label>
                <Input
                    id="display_name"
                    value={data.display_name}
                    onChange={(e) => setData('display_name', e.target.value)}
                    required
                    className="mt-1"
                />
                <InputError message={errors.display_name} />
            </div>
            <div>
                <Label htmlFor="type">Type</Label>
                <select
                    id="type"
                    value={data.type}
                    disabled={typeLocked}
                    onChange={(e) => setData('type', e.target.value as ChannelType)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:bg-gray-100"
                >
                    {CHANNEL_TYPES.map((type) => (
                        <option key={type} value={type}>
                            {type.replace(/_/g, ' ')}
                        </option>
                    ))}
                </select>
                <InputError message={errors.type} />
            </div>
            <div>
                <Label htmlFor="platform">Platform</Label>
                <Input
                    id="platform"
                    value={data.platform}
                    onChange={(e) => setData('platform', e.target.value)}
                    className="mt-1"
                />
                <InputError message={errors.platform} />
            </div>
            <div>
                <Label htmlFor="status">Status</Label>
                <select
                    id="status"
                    value={data.status}
                    onChange={(e) => setData('status', e.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                    {CHANNEL_STATUSES.map((status) => (
                        <option key={status} value={status}>
                            {status}
                        </option>
                    ))}
                </select>
                <InputError message={errors.status} />
            </div>
            <div>
                <Label htmlFor="api_key">Credentials / API key (stored in settings)</Label>
                <Input
                    id="api_key"
                    value={data.settings.api_key}
                    onChange={(e) => setSettingsApiKey(e.target.value)}
                    autoComplete="off"
                    className="mt-1"
                />
                <InputError message={errors['settings.api_key']} />
            </div>
            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <Label htmlFor="follower_count">Influencer followers</Label>
                    <Input
                        id="follower_count"
                        type="number"
                        min={0}
                        value={data.follower_count}
                        onChange={(e) => setData('follower_count', e.target.value)}
                        className="mt-1"
                    />
                    <InputError message={errors.follower_count} />
                </div>
                <div>
                    <Label htmlFor="engagement_rate">Engagement %</Label>
                    <Input
                        id="engagement_rate"
                        type="number"
                        step="0.01"
                        value={data.engagement_rate}
                        onChange={(e) => setData('engagement_rate', e.target.value)}
                        className="mt-1"
                    />
                    <InputError message={errors.engagement_rate} />
                </div>
            </div>
            <div>
                <Label htmlFor="handle">Handle</Label>
                <Input
                    id="handle"
                    value={data.handle}
                    onChange={(e) => setData('handle', e.target.value)}
                    className="mt-1"
                />
                <InputError message={errors.handle} />
            </div>
        </div>
    );
}
