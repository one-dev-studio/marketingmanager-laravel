export interface StorageProviderRow {
    provider: string;
    label: string;
    is_connected: boolean;
}

export interface StorageQuota {
    used_human: string;
    limit_human: string;
}
