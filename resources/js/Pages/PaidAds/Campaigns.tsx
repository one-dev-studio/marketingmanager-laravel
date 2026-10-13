import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface PaidCampaignItem {
    id: number;
    name: string;
    platform: string;
    status: string;
    budget: string | number;
    budget_type: string;
    currency: string;
    spent: string | number;
}

interface PaginatedPaidCampaigns {
    data: PaidCampaignItem[];
}

export interface PaidAdsCampaignsProps {
    organizationId: string;
    paidCampaigns: PaginatedPaidCampaigns;
    platforms: string[];
}

export default function Campaigns({ organizationId, paidCampaigns, platforms }: PaidAdsCampaignsProps) {
    const form = useForm({
        name: '',
        platform: 'facebook',
        budget: '',
        budget_type: 'daily',
        start_date: '',
        end_date: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.paid-campaigns.store', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => form.reset('name', 'budget', 'end_date'),
        });
    };

    const items = paidCampaigns.data ?? [];

    return (
        <AppLayout title="Paid Ads">
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Paid ad campaigns</h1>
                    <p className="text-sm text-gray-600">Budget, schedule, and performance</p>
                </div>
                <form
                    onSubmit={submit}
                    className="grid gap-3 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-4"
                >
                    <input
                        required
                        placeholder="Campaign name"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.name}
                        onChange={(e) => form.setData('name', e.target.value)}
                    />
                    <select
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.platform}
                        onChange={(e) => form.setData('platform', e.target.value)}
                    >
                        {platforms.map((platform) => (
                            <option key={platform} value={platform}>{platform}</option>
                        ))}
                    </select>
                    <input
                        required
                        type="number"
                        step="0.01"
                        placeholder="Budget"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.budget}
                        onChange={(e) => form.setData('budget', e.target.value)}
                    />
                    <select
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.budget_type}
                        onChange={(e) => form.setData('budget_type', e.target.value)}
                    >
                        <option value="daily">Daily</option>
                        <option value="lifetime">Lifetime</option>
                    </select>
                    <input
                        required
                        type="date"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.start_date}
                        onChange={(e) => form.setData('start_date', e.target.value)}
                    />
                    <input
                        type="date"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.end_date}
                        onChange={(e) => form.setData('end_date', e.target.value)}
                    />
                    <Button type="submit" disabled={form.processing}>Create</Button>
                </form>
                <div className="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No paid campaigns yet.</div>
                    ) : (
                        items.map((campaign) => (
                            <div key={campaign.id} className="flex justify-between gap-4 p-4">
                                <div>
                                    <h2 className="font-medium text-gray-900">{campaign.name}</h2>
                                    <p className="text-sm text-gray-500">
                                        {campaign.platform} · {campaign.status} · {campaign.currency} {campaign.budget} (
                                        {campaign.budget_type})
                                    </p>
                                </div>
                                <p className="text-sm text-gray-600">Spent {campaign.spent}</p>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
