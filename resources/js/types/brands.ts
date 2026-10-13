export interface BrandPermissions {
    update: boolean;
    delete: boolean;
}

export interface BrandListItem {
    id: number;
    name: string;
    status: string | null;
    summary: string | null;
    guidelines: string | null;
    can: BrandPermissions;
}

export interface BrandDetail {
    id: number;
    name: string;
    summary: string | null;
    audience: string | null;
    guidelines: string | null;
    tone_of_voice: string | null;
    keywords: string[];
    avoid_keywords: string[];
    status: string | null;
    business_model: string | null;
    can: BrandPermissions;
}
