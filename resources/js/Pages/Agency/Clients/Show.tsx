import AgencyLayout from '@/Layouts/AgencyLayout';
import { Link } from '@inertiajs/react';

interface ClientDetail {
    id: number;
    name: string;
    users_count: number;
    status: string;
    workspace_href: string;
}

export interface AgencyClientsShowProps {
    agencyId: string;
    client: ClientDetail;
}

export default function Show({ client }: AgencyClientsShowProps) {
    return (
        <AgencyLayout title={client.name}>
            <div className="space-y-4">
                <h2 className="text-2xl font-semibold text-gray-900">{client.name}</h2>
                <p className="text-sm text-gray-600">
                    {client.users_count} users · {client.status}
                </p>
                <Link href={client.workspace_href} className="text-primary-700 hover:underline">
                    Open customer workspace
                </Link>
            </div>
        </AgencyLayout>
    );
}
