export type LandingPageBlockType = 'hero' | 'text' | 'cta' | 'form';

export interface LandingPageBlock {
    type: LandingPageBlockType;
    value: string;
}

export interface LandingPageListItem {
    id: number;
    name: string;
    slug: string;
    status: string;
    is_active: boolean;
    can: {
        update: boolean;
    };
}

export interface LandingPageBuilderData {
    id: number;
    name: string;
    slug: string;
    custom_domain: string | null;
    html_content: string | null;
    page_data: {
        blocks?: LandingPageBlock[];
    };
    seo_settings: {
        title?: string;
        description?: string;
    };
    status: string;
    variants: Array<{
        id: number;
        name: string;
        traffic_percentage: number;
    }>;
    can: {
        update: boolean;
    };
}
