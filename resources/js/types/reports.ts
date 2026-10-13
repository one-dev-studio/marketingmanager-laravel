export type ReportChartType = 'bar' | 'line' | 'pie';

export interface ReportWidget {
    type: ReportChartType;
}

export interface ReportListItem {
    id: number;
    name: string;
    type: string;
    last_generated_at: string | null;
    can: {
        update: boolean;
    };
}

export interface ReportBuilderData {
    id: number | null;
    name: string;
    type: string;
    config: {
        widgets: ReportWidget[];
    };
    can: {
        update: boolean;
    };
}
