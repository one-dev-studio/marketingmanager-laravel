export interface CampaignListItem {
    id: number;
    name: string;
    status: string;
    brand_name: string | null;
    show_href: string;
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
