import { Button } from '@/components/ui/button';
import AgencyLayout from '@/Layouts/AgencyLayout';
import { Link, router } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface ClientListItem {
    id: number;
    name: string;
    users_count: number;
    show_href: string;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedClients {
    data: ClientListItem[];
    links: PaginationLink[];
}

export interface AgencyClientsIndexProps {
    agencyId: string;
    clients: PaginatedClients;
    filters: {
        search: string | null;
        status: string | null;
    };
    can: {
        create: boolean;
    };
}

export default function Index({ agencyId, clients, filters, can }: AgencyClientsIndexProps) {
    const submitSearch = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const form = event.currentTarget;
        const search = (form.elements.namedItem('search') as HTMLInputElement).value;
        router.get(route('agency.clients.index', { agency: agencyId }), { search }, { preserveState: true });
    };

    const items = clients.data ?? [];

    return (
        <AgencyLayout title="Clients">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 className="text-2xl font-semibold text-gray-900">Clients</h2>
                        <p className="text-sm text-gray-600">Organizations managed by this agency</p>
                    </div>
                    {can.create ? (
                        <Button asChild>
                            <Link href={route('agency.clients.create', { agency: agencyId })}>Add New Client</Link>
                        </Button>
                    ) : null}
                </div>

                <form onSubmit={submitSearch} className="flex gap-2">
                    <input
                        name="search"
                        defaultValue={filters.search ?? ''}
                        className="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Search clients"
                    />
                    <Button type="submit" variant="outline">Search</Button>
                </form>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <table className="min-w-full text-sm">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="p-3 text-left font-medium text-gray-700">Organization</th>
                                <th className="p-3 text-left font-medium text-gray-700">Users</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {items.length === 0 ? (
                                <tr>
                                    <td colSpan={3} className="p-8 text-center text-gray-500">No clients yet.</td>
                                </tr>
                            ) : (
                                items.map((client) => (
                                    <tr key={client.id} className="border-t border-gray-100">
                                        <td className="p-3">{client.name}</td>
                                        <td className="p-3">{client.users_count}</td>
                                        <td className="p-3 text-right">
                                            <Link href={client.show_href} className="text-primary-700 hover:underline">
                                                View Organization
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {clients.links && clients.links.length > 1 ? (
                    <nav className="flex flex-wrap gap-1">
                        {clients.links.map((link) =>
                            link.url ? (
                                <Link
                                    key={link.label}
                                    href={link.url}
                                    className={`rounded border px-3 py-1 text-sm ${
                                        link.active ? 'border-primary-600 bg-primary-50 text-primary-700' : 'border-gray-200'
                                    }`}
                                    preserveState
                                >
                                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                                </Link>
                            ) : (
                                <span
                                    key={link.label}
                                    className="rounded border border-gray-100 px-3 py-1 text-sm text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ),
                        )}
                    </nav>
                ) : null}
            </div>
        </AgencyLayout>
    );
}
