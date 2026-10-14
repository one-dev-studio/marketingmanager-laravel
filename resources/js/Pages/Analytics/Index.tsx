import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import { FormEvent, useState } from 'react';
import { route } from 'ziggy-js';

interface CampaignOption {
    id: number;
    name: string;
}

interface ReportSummary {
    id: number;
    title: string;
    created_at: string | null;
}

export interface AnalyticsIndexProps {
    organizationId: string;
    brandId: string;
    brandName: string;
    campaigns: CampaignOption[];
    reports: ReportSummary[];
}

export default function Index({
    organizationId,
    brandId,
    brandName,
    campaigns,
    reports,
}: AnalyticsIndexProps) {
    const [campaignId, setCampaignId] = useState('');
    const [loading, setLoading] = useState(false);
    const [result, setResult] = useState<unknown>(null);

    const analyze = async (event: FormEvent) => {
        event.preventDefault();
        if (!campaignId) {
            return;
        }
        setLoading(true);
        try {
            const { data } = await axios.post(
                route('main.analytics.analyze', { organizationId }),
                { campaign_id: Number(campaignId) },
                { params: { brandId } },
            );
            setResult(data);
        } catch (error: unknown) {
            const message =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Analysis failed';
            setResult({ error: message });
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title="Analytics">
            <div className="grid gap-6 lg:grid-cols-3">
                <form onSubmit={analyze} className="space-y-3 rounded-lg border border-gray-200 bg-white p-4">
                    <h1 className="text-xl font-semibold text-gray-900">Analyze</h1>
                    <p className="text-sm text-gray-500">{brandName}</p>
                    <select
                        name="campaign_id"
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={campaignId}
                        onChange={(e) => setCampaignId(e.target.value)}
                        required
                    >
                        <option value="">Campaign / scheduled posts</option>
                        {campaigns.map((campaign) => (
                            <option key={campaign.id} value={campaign.id}>
                                {campaign.name}
                            </option>
                        ))}
                    </select>
                    <Button type="submit" className="w-full" disabled={loading}>
                        {loading ? 'Analyzing…' : 'Analyze'}
                    </Button>
                    {reports.length > 0 && (
                        <p className="text-xs text-gray-400">Saved reports: {reports.length}</p>
                    )}
                </form>
                <div className="min-h-[20rem] rounded-lg border border-gray-200 bg-white p-4 lg:col-span-2">
                    {!result && <p className="text-gray-500">Results appear here.</p>}
                    {result !== null && (
                        <pre className="whitespace-pre-wrap text-sm text-gray-800">
                            {JSON.stringify(result, null, 2)}
                        </pre>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
