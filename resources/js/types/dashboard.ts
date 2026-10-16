export type DashboardWidgetType =
    | 'kpi'
    | 'activity_feed'
    | 'pending_tasks'
    | 'calendar_preview'
    | 'campaign_performance';

export interface DashboardWidgetRecord {
    id: number;
    widget_type: DashboardWidgetType;
    title: string;
    config: Record<string, unknown> | null;
    position_x: number;
    position_y: number;
    width: number;
    height: number;
    is_visible: boolean;
}

export interface DashboardWidgetTypeOption {
    type: DashboardWidgetType;
    name: string;
    icon: string;
}

export interface DashboardKpiItem {
    name: string;
    value: number;
    format?: string;
    change?: number;
}

export interface DashboardActivityItem {
    id: number | string;
    description: string;
    created_at: string;
    user?: { name: string } | null;
}

export interface DashboardPendingTaskItem {
    id: number | string;
    title: string;
    due_date?: string | null;
    priority?: string;
}

export interface DashboardCalendarEventItem {
    id: number | string;
    title: string;
    start: string;
    type?: string;
}

export interface DashboardCampaignPerformanceItem {
    id: number | string;
    name: string;
    status: string;
    engagement: number;
    reach: number;
    roi: number;
}
