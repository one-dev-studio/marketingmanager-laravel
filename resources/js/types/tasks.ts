export interface TaskMember {
    id: number;
    name: string;
}

export interface TaskTemplateSummary {
    id: number;
    name: string;
}

export interface TaskComment {
    id: number;
    comment?: string;
    body?: string;
    user?: {
        id: number;
        name: string;
    };
}

export interface TaskKanbanItem {
    id: number;
    organization_id: number;
    title: string;
    description: string | null;
    status: string;
    due_date: string | null;
    assignee_id: number | null;
    assignee?: TaskMember | null;
    comments?: TaskComment[];
}
