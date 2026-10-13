export interface CampaignListItem {
    id: number;
    name: string;
    status: string;
    brand_name: string | null;
    show_href: string;
}

export interface CampaignGoalItem {
    id: number;
    name: string;
    description: string | null;
    target_value: string | number | null;
    current_value: string | number | null;
    target_date: string | null;
}

export interface CampaignScheduledPostItem {
    id: number;
    content: string;
    scheduled_at: string | null;
    status: string;
}

export interface CampaignDetail {
    id: number;
    name: string;
    description: string | null;
    status: string;
    start_date: string | null;
    end_date: string | null;
    budget: string | number;
    spent: string | number;
    brand_name: string | null;
    goals: CampaignGoalItem[];
    scheduled_posts: CampaignScheduledPostItem[];
    index_href: string;
    can: {
        update: boolean;
        delete: boolean;
    };
}

export interface CampaignBrandOption {
    id: number;
    name: string;
}

export interface CampaignChannelOption {
    id: number;
    display_name: string;
}

export interface CampaignCreateDefaults {
    start_date: string;
    end_date: string;
}
