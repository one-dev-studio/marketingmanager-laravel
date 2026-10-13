import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type {
    CampaignBrandOption,
    CampaignChannelOption,
    CampaignCreateDefaults,
} from '@/types/campaigns';
import { Link, useForm } from '@inertiajs/react';
import { useMemo, useState, type FormEvent } from 'react';
import { route } from 'ziggy-js';

const STEPS = ['Plan', 'Content', 'Review'] as const;

interface CampaignFormData {
    name: string;
    description: string;
    goal_type: string;
    brand_id: string;
    start_date: string;
    end_date: string;
    budget: string;
    channel_ids: number[];
}

export interface CampaignsCreateProps {
    organizationId: string;
    prefillBrandId: string | null;
    brands: CampaignBrandOption[];
    channels: CampaignChannelOption[];
    defaults: CampaignCreateDefaults;
}

export default function Create({
    organizationId,
    prefillBrandId,
    brands,
    channels,
    defaults,
}: CampaignsCreateProps) {
    const [step, setStep] = useState(0);

    const form = useForm<CampaignFormData>({
        name: '',
        description: '',
        goal_type: 'product_launch',
        brand_id: prefillBrandId ?? '',
        start_date: defaults.start_date,
        end_date: defaults.end_date,
        budget: '0',
        channel_ids: [],
    });

    const selectedChannels = useMemo(
        () => channels.filter((channel) => form.data.channel_ids.includes(channel.id)),
        [channels, form.data.channel_ids],
    );

    const toggleChannel = (channelId: number) => {
        const exists = form.data.channel_ids.includes(channelId);
        form.setData(
            'channel_ids',
            exists
                ? form.data.channel_ids.filter((id) => id !== channelId)
                : [...form.data.channel_ids, channelId],
        );
    };

    const canAdvanceFromPlan =
        form.data.name.trim().length > 0 &&
        form.data.start_date.length > 0 &&
        form.data.channel_ids.length > 0;

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.transform((data) => ({
            name: data.name,
            description: data.description || null,
            goal_type: data.goal_type,
            brand_id: data.brand_id ? Number(data.brand_id) : null,
            start_date: data.start_date,
            end_date: data.end_date || null,
            budget: Number(data.budget),
            channels: data.channel_ids.map((id) => ({
                id,
                budget: Number(data.budget) / data.channel_ids.length,
            })),
        }));
        form.post(route('main.campaigns.store', { organizationId }));
    };

    return (
        <AppLayout title="Create Campaign">
            <div className="mx-auto max-w-4xl space-y-6">
                <div className="mb-4 flex max-w-2xl items-center justify-between">
                    {STEPS.map((label, index) => (
                        <div key={label} className="flex flex-1 flex-col items-center">
                            <div
                                className={`flex h-10 w-10 items-center justify-center rounded-full font-semibold ${
                                    step === index
                                        ? 'bg-primary-600 text-white'
                                        : step > index
                                          ? 'bg-green-500 text-white'
                                          : 'bg-gray-200 text-gray-600'
                                }`}
                            >
                                {step > index ? '✓' : index + 1}
                            </div>
                            <span className="mt-2 text-sm">{label}</span>
                        </div>
                    ))}
                </div>

                <form
                    onSubmit={submit}
                    className="space-y-4 rounded-lg border border-gray-200 bg-white p-6"
                >
                    {step === 0 ? (
                        <div className="space-y-4">
                            <h2 className="text-xl font-semibold">Plan your campaign</h2>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    Campaign name
                                </label>
                                <input
                                    value={form.data.name}
                                    onChange={(e) => form.setData('name', e.target.value)}
                                    required
                                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                    placeholder="Summer launch"
                                />
                                {form.errors.name ? (
                                    <p className="mt-1 text-sm text-red-600">{form.errors.name}</p>
                                ) : null}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    Campaign goal
                                </label>
                                <textarea
                                    value={form.data.description}
                                    onChange={(e) => form.setData('description', e.target.value)}
                                    rows={3}
                                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                    placeholder="What do you want to achieve?"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    Goal type
                                </label>
                                <select
                                    value={form.data.goal_type}
                                    onChange={(e) => form.setData('goal_type', e.target.value)}
                                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                >
                                    <option value="product_launch">Product Launch</option>
                                    <option value="brand_awareness">Brand Awareness</option>
                                    <option value="lead_generation">Lead Generation</option>
                                    <option value="event_promotion">Event Promotion</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Brand</label>
                                <select
                                    value={form.data.brand_id}
                                    onChange={(e) => form.setData('brand_id', e.target.value)}
                                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                >
                                    <option value="">Select brand</option>
                                    {brands.map((brand) => (
                                        <option key={brand.id} value={brand.id}>
                                            {brand.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <label className="mb-2 block text-sm font-medium text-gray-700">
                                    Channels
                                </label>
                                <div className="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    {channels.length === 0 ? (
                                        <p className="text-sm text-gray-500">
                                            No active channels. Add a channel first.
                                        </p>
                                    ) : (
                                        channels.map((channel) => (
                                            <label
                                                key={channel.id}
                                                className="flex items-center gap-2 text-sm"
                                            >
                                                <input
                                                    type="checkbox"
                                                    checked={form.data.channel_ids.includes(
                                                        channel.id,
                                                    )}
                                                    onChange={() => toggleChannel(channel.id)}
                                                />
                                                {channel.display_name}
                                            </label>
                                        ))
                                    )}
                                </div>
                                {'channels' in form.errors && form.errors.channels ? (
                                    <p className="mt-1 text-sm text-red-600">
                                        {String(form.errors.channels)}
                                    </p>
                                ) : null}
                            </div>
                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        Start date
                                    </label>
                                    <input
                                        type="date"
                                        required
                                        value={form.data.start_date}
                                        onChange={(e) => form.setData('start_date', e.target.value)}
                                        className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">
                                        End date
                                    </label>
                                    <input
                                        type="date"
                                        value={form.data.end_date}
                                        onChange={(e) => form.setData('end_date', e.target.value)}
                                        className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                    />
                                </div>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">
                                    Budget
                                </label>
                                <input
                                    type="number"
                                    min={0}
                                    step="0.01"
                                    required
                                    value={form.data.budget}
                                    onChange={(e) => form.setData('budget', e.target.value)}
                                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                />
                            </div>
                            <Button
                                type="button"
                                disabled={!canAdvanceFromPlan}
                                onClick={() => setStep(1)}
                            >
                                Continue to content
                            </Button>
                        </div>
                    ) : null}

                    {step === 1 ? (
                        <div className="space-y-4">
                            <h2 className="text-xl font-semibold">Content</h2>
                            <p className="text-sm text-gray-600">
                                Save the campaign plan now. You can generate channel content from the
                                campaign page next.
                            </p>
                            <div className="flex gap-2">
                                <Button type="button" variant="outline" onClick={() => setStep(0)}>
                                    Back
                                </Button>
                                <Button type="button" onClick={() => setStep(2)}>
                                    Continue to review
                                </Button>
                            </div>
                        </div>
                    ) : null}

                    {step === 2 ? (
                        <div className="space-y-4">
                            <h2 className="text-xl font-semibold">Review &amp; create</h2>
                            <dl className="space-y-2 text-sm text-gray-700">
                                <div>
                                    <dt className="font-medium text-gray-500">Name</dt>
                                    <dd>{form.data.name}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-500">Channels</dt>
                                    <dd>
                                        {selectedChannels.length > 0
                                            ? selectedChannels
                                                  .map((channel) => channel.display_name)
                                                  .join(', ')
                                            : '—'}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-500">Dates</dt>
                                    <dd>
                                        {form.data.start_date} → {form.data.end_date || '—'}
                                    </dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-500">Budget</dt>
                                    <dd>${form.data.budget}</dd>
                                </div>
                            </dl>
                            <p className="text-sm text-gray-600">
                                Create the campaign as a draft, then submit content for review from the
                                campaign list.
                            </p>
                            <div className="flex gap-2">
                                <Button type="button" variant="outline" onClick={() => setStep(1)}>
                                    Back
                                </Button>
                                <Button type="submit" disabled={form.processing}>
                                    Create campaign
                                </Button>
                            </div>
                        </div>
                    ) : null}
                </form>

                <Link
                    href={route('main.campaigns.index', { organizationId })}
                    className="text-sm text-primary-600 hover:text-primary-800"
                >
                    Cancel
                </Link>
            </div>
        </AppLayout>
    );
}
