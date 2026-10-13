import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { CampaignOption } from '@/types/intelligence';
import axios from 'axios';
import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface IntelligencePredictiveProps {
    organizationId: string;
    campaigns: CampaignOption[];
}

export default function Predictive({ organizationId, campaigns }: IntelligencePredictiveProps) {
    const [campaignId, setCampaignId] = useState('');
    const [output, setOutput] = useState('');
    const [loading, setLoading] = useState(false);

    const forecast = async () => {
        if (!campaignId) {
            return;
        }
        setLoading(true);
        setOutput('');
        try {
            const { data } = await axios.post(
                route('main.analytics.predictions.campaigns', {
                    organizationId,
                    campaignId,
                }),
            );
            setOutput(JSON.stringify(data.data ?? data, null, 2));
        } catch (error: unknown) {
            const message =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Forecast failed.';
            setOutput(message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title="Predictive analytics">
            <div className="space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">Predictive analytics</h1>
                <select
                    value={campaignId}
                    className="w-full max-w-md rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setCampaignId(event.target.value)}
                >
                    <option value="">Campaign</option>
                    {campaigns.map((campaign) => (
                        <option key={campaign.id} value={String(campaign.id)}>
                            {campaign.name}
                        </option>
                    ))}
                </select>
                <Button type="button" disabled={loading || !campaignId} onClick={() => void forecast()}>
                    {loading ? 'Forecasting…' : 'Forecast'}
                </Button>
                <div className="flex gap-4 text-sm">
                    <Link
                        href={route('main.analytics.predictions.optimal-posting-times', { organizationId })}
                        className="text-indigo-600 hover:text-indigo-800"
                    >
                        Posting times
                    </Link>
                    <Link
                        href={route('main.analytics.predictions.budget-optimization', { organizationId })}
                        className="text-indigo-600 hover:text-indigo-800"
                    >
                        Budget
                    </Link>
                </div>
                {output ? (
                    <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm">
                        {output}
                    </pre>
                ) : null}
            </div>
        </AppLayout>
    );
}
