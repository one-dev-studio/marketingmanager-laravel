export const CHANNEL_TYPES = [
    'social',
    'email',
    'whatsapp',
    'amplify',
    'paid_ads',
    'press_release',
    'influencer',
] as const;

export type ChannelType = (typeof CHANNEL_TYPES)[number];

export const CHANNEL_STATUSES = ['active', 'inactive', 'disconnected'] as const;

export type ChannelStatus = (typeof CHANNEL_STATUSES)[number];

export interface ChannelPermissions {
    update: boolean;
    delete: boolean;
}

export interface ChannelListItem {
    id: number;
    display_name: string;
    type: ChannelType;
    platform: string | null;
    status: ChannelStatus | string | null;
    connected: boolean;
    connection_status_label: string;
    has_social_connection: boolean;
    social_connection_id: number | null;
    follower_count: number | null;
    engagement_rate: number | null;
    handle: string | null;
    api_key: string;
    can: ChannelPermissions;
}

export interface ChannelFormChannel extends ChannelListItem {}
