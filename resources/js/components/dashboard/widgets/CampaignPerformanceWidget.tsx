import { WidgetLoading } from '@/components/dashboard/WidgetLoading';
import type { DashboardCampaignPerformanceItem } from '@/types/dashboard';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { route } from 'ziggy-js';

function formatNumber(value: number): string {
    if (value >= 1_000_000) {
        return `${(value / 1_000_000).toFixed(1)}M`;
    }
    if (value >= 1_000) {
        return `${(value / 1_000).toFixed(1)}K`;
    }
    return value.toString();
}

function statusClass(status: string): string {
    const normalized = status.toLowerCase();
    if (normalized === 'active') {
        return 'bg-green-100 text-green-800';
    }
    if (normalized === 'paused') {
        return 'bg-yellow-100 text-yellow-800';
    }
    if (normalized === 'completed') {
        return 'bg-blue-100 text-blue-800';
    }
    if (normalized === 'draft') {
        return 'bg-gray-100 text-gray-800';
    }
    return 'bg-gray-100 text-gray-800';
}

export interface CampaignPerformanceWidgetProps {
    organizationId: string;
}

export function CampaignPerformanceWidget({ organizationId }: CampaignPerformanceWidgetProps) {
    const [performance, setPerformance] = useState<DashboardCampaignPerformanceItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let cancelled = false;

        const load = async () => {
            try {
                setLoading(true);
                const response = await axios.get<{
                    data: { campaign_performance?: DashboardCampaignPerformanceItem[] };
                }>(route('main.dashboard', { organizationId }), {
                    headers: { Accept: 'application/json' },
                });
                if (!cancelled) {
                    setPerformance(response.data.data?.campaign_performance ?? []);
                }
            } catch {
                if (!cancelled) {
                    setPerformance([]);
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        };

        void load();
        const interval = window.setInterval(load, 300_000);

        return () => {
            cancelled = true;
            window.clearInterval(interval);
        };
    }, [organizationId]);

    if (loading) {
        return <WidgetLoading />;
    }

    if (performance.length === 0) {
        return <p className="py-8 text-center text-gray-500">No campaign data available</p>;
    }

    return (
        <div className="space-y-3">
            {performance.map((campaign) => (
                <div key={campaign.id} className="rounded-lg bg-gray-50 p-3">
                    <div className="mb-2 flex items-center justify-between">
                        <h4 className="truncate text-sm font-medium text-gray-900">{campaign.name}</h4>
                        <span
                            className={`rounded-full px-2 py-1 text-xs font-medium ${statusClass(campaign.status)}`}
                        >
                            {campaign.status}
                        </span>
                    </div>
                    <div className="grid grid-cols-3 gap-2 text-xs">
                        <div>
                            <div className="text-gray-500">Engagement</div>
                            <div className="font-semibold text-gray-900">
                                {formatNumber(campaign.engagement)}
                            </div>
                        </div>
                        <div>
                            <div className="text-gray-500">Reach</div>
                            <div className="font-semibold text-gray-900">
                                {formatNumber(campaign.reach)}
                            </div>
                        </div>
                        <div>
                            <div className="text-gray-500">ROI</div>
                            <div
                                className={`font-semibold ${
                                    campaign.roi >= 0 ? 'text-green-600' : 'text-red-600'
                                }`}
                            >
                                {campaign.roi >= 0 ? '+' : ''}
                                {campaign.roi}%
                            </div>
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
}
