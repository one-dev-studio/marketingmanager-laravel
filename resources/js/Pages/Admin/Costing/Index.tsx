import AdminLayout from '@/Layouts/AdminLayout';
import { router } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface AdminCostingIndexProps {
    costing: Record<string, unknown>;
    platformAnalytics: Record<string, unknown>;
    systemHealth: Record<string, unknown>;
    filters: {
        date_from?: string | null;
        date_to?: string | null;
        organization_id?: string | null;
    };
}

export default function Index({ costing, platformAnalytics, systemHealth, filters }: AdminCostingIndexProps) {
    const submitFilter = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const form = event.currentTarget;
        const dateFrom = (form.elements.namedItem('date_from') as HTMLInputElement).value;
        const dateTo = (form.elements.namedItem('date_to') as HTMLInputElement).value;
        router.get(route('admin.costing.index'), { date_from: dateFrom, date_to: dateTo }, { preserveState: true });
    };

    return (
        <AdminLayout title="AI Costing">
            <div className="space-y-6">
                <h2 className="text-2xl font-bold text-gray-900">AI costing</h2>
                <form onSubmit={submitFilter} className="flex flex-wrap gap-2">
                    <input
                        type="date"
                        name="date_from"
                        defaultValue={filters.date_from ?? ''}
                        className="rounded-md border border-gray-300 px-3 py-2"
                    />
                    <input
                        type="date"
                        name="date_to"
                        defaultValue={filters.date_to ?? ''}
                        className="rounded-md border border-gray-300 px-3 py-2"
                    />
                    <button type="submit" className="rounded-md border px-3 py-2">Filter</button>
                </form>
                <pre className="overflow-auto rounded border bg-gray-50 p-4 text-sm">{JSON.stringify(costing, null, 2)}</pre>
                <h3 className="font-semibold text-gray-900">Platform analytics</h3>
                <pre className="overflow-auto rounded border bg-gray-50 p-4 text-sm">{JSON.stringify(platformAnalytics, null, 2)}</pre>
                <h3 className="font-semibold text-gray-900">System health</h3>
                <pre className="overflow-auto rounded border bg-gray-50 p-4 text-sm">{JSON.stringify(systemHealth, null, 2)}</pre>
            </div>
        </AdminLayout>
    );
}
