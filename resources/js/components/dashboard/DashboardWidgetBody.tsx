import { ActivityFeedWidget } from '@/components/dashboard/widgets/ActivityFeedWidget';
import { CalendarPreviewWidget } from '@/components/dashboard/widgets/CalendarPreviewWidget';
import { CampaignPerformanceWidget } from '@/components/dashboard/widgets/CampaignPerformanceWidget';
import { KpiWidget } from '@/components/dashboard/widgets/KpiWidget';
import { PendingTasksWidget } from '@/components/dashboard/widgets/PendingTasksWidget';
import type { DashboardWidgetRecord, DashboardWidgetType } from '@/types/dashboard';

export interface DashboardWidgetBodyProps {
    organizationId: string;
    widgetType: DashboardWidgetType;
}

export function DashboardWidgetBody({ organizationId, widgetType }: DashboardWidgetBodyProps) {
    switch (widgetType) {
        case 'kpi':
            return <KpiWidget organizationId={organizationId} />;
        case 'activity_feed':
            return <ActivityFeedWidget organizationId={organizationId} />;
        case 'pending_tasks':
            return <PendingTasksWidget organizationId={organizationId} />;
        case 'calendar_preview':
            return <CalendarPreviewWidget organizationId={organizationId} />;
        case 'campaign_performance':
            return <CampaignPerformanceWidget organizationId={organizationId} />;
        default:
            return <p className="py-8 text-center text-gray-500">Unsupported widget type.</p>;
    }
}

export function isDashboardWidgetRecord(value: unknown): value is DashboardWidgetRecord {
    if (typeof value !== 'object' || value === null) {
        return false;
    }
    const record = value as DashboardWidgetRecord;
    return typeof record.id === 'number' && typeof record.widget_type === 'string';
}
