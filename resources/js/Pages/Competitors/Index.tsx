import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { useForm } from '@inertiajs/react';
import axios from 'axios';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface CompetitorItem {
    id: number;
    name: string;
    website: string | null;
    analyses_count: number;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedCompetitors {
    data: CompetitorItem[];
    links: PaginationLink[];
}

export interface CompetitorsIndexProps {
    organizationId: string;
    competitors: PaginatedCompetitors;
}

export default function Index({ organizationId, competitors }: CompetitorsIndexProps) {
    const form = useForm({ name: '', website: '' });
    const items = competitors.data ?? [];

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.competitors.store', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    };

    const runAnalysis = async (competitorId: number) => {
        await axios.post(route('main.competitors.analysis', { organizationId, competitor: competitorId }), {
            analysis_type: 'overview',
        });
    };

    return (
        <AppLayout title="Competitors">
            <div className="space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Competitor analysis</h1>
                <form
                    onSubmit={submit}
                    className="grid gap-3 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-3"
                >
                    <input
                        required
                        name="name"
                        placeholder="Competitor name"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.name}
                        onChange={(e) => form.setData('name', e.target.value)}
                    />
                    <input
                        name="website"
                        placeholder="https://"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.website}
                        onChange={(e) => form.setData('website', e.target.value)}
                    />
                    <Button type="submit" disabled={form.processing}>Add competitor</Button>
                </form>
                <div className="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No competitors tracked.</div>
                    ) : (
                        items.map((competitor) => (
                            <div key={competitor.id} className="flex items-center justify-between gap-4 p-4">
                                <div>
                                    <h2 className="font-medium text-gray-900">{competitor.name}</h2>
                                    <p className="text-sm text-gray-500">
                                        {competitor.website ?? '—'} · {competitor.analyses_count} analyses
                                    </p>
                                </div>
                                <Button type="button" variant="outline" onClick={() => runAnalysis(competitor.id)}>
                                    Run analysis
                                </Button>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
