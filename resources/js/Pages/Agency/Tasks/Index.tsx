import { AgencyTaskKanban } from '@/components/agency/AgencyTaskKanban';
import AgencyLayout from '@/Layouts/AgencyLayout';
import type { TaskKanbanItem } from '@/types/tasks';

interface ClientOption {
    id: number;
    name: string;
}

export interface AgencyTasksIndexProps {
    agencyId: string;
    clients: ClientOption[];
    tasks: TaskKanbanItem[];
}

export default function Index({ agencyId, clients, tasks }: AgencyTasksIndexProps) {
    return (
        <AgencyLayout title="Tasks">
            <AgencyTaskKanban agencyId={agencyId} clients={clients} initialTasks={tasks} />
        </AgencyLayout>
    );
}
