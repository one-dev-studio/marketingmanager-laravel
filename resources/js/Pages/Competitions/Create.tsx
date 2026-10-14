import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface CampaignOption {
    id: number;
    name: string;
}

export interface CompetitionsCreateProps {
    organizationId: string;
    campaigns: CampaignOption[];
}

export default function Create({ organizationId, campaigns }: CompetitionsCreateProps) {
    const form = useForm({
        name: '',
        description: '',
        prize: '',
        campaign_id: '',
        starts_at: '',
        ends_at: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.competitions.store', { organizationId }));
    };

    return (
        <AppLayout title="Create competition">
            <form
                onSubmit={submit}
                className="max-w-xl space-y-3 rounded-lg border border-gray-200 bg-white p-6"
            >
                <input
                    name="name"
                    required
                    placeholder="Name"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.name}
                    onChange={(e) => form.setData('name', e.target.value)}
                />
                <textarea
                    name="description"
                    placeholder="Rules / description"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.description}
                    onChange={(e) => form.setData('description', e.target.value)}
                />
                <input
                    name="prize"
                    placeholder="Prize"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.prize}
                    onChange={(e) => form.setData('prize', e.target.value)}
                />
                <select
                    name="campaign_id"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.campaign_id}
                    onChange={(e) => form.setData('campaign_id', e.target.value)}
                >
                    <option value="">Tied campaign (optional)</option>
                    {campaigns.map((campaign) => (
                        <option key={campaign.id} value={campaign.id}>
                            {campaign.name}
                        </option>
                    ))}
                </select>
                <div className="grid grid-cols-2 gap-2">
                    <input
                        type="datetime-local"
                        name="starts_at"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.starts_at}
                        onChange={(e) => form.setData('starts_at', e.target.value)}
                    />
                    <input
                        type="datetime-local"
                        name="ends_at"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.ends_at}
                        onChange={(e) => form.setData('ends_at', e.target.value)}
                    />
                </div>
                <Button type="submit" disabled={form.processing}>Create</Button>
            </form>
        </AppLayout>
    );
}
