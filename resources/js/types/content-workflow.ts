export interface CalendarEventItem {
    id: string | number;
    title: string;
    description: string | null;
    type: string;
    start: string;
    end: string | null;
    all_day: boolean;
    status: string | null;
}

export interface ContentApprovalListItem {
    id: number;
    status: string;
    requested_at: string | null;
    campaign_name: string | null;
    channel_name: string | null;
    content_preview: string;
    requested_by_name: string | null;
    show_href: string;
}
