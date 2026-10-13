import { WidgetLoading } from '@/components/dashboard/WidgetLoading';
import type { DashboardKpiItem } from '@/types/dashboard';
import axios from 'axios';
import { useEffect, useState } from 'react';
import { route } from 'ziggy-js';

function formatValue(value: number, format?: string): string {
    if (format === 'currency') {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);
    }
    if (format === 'percentage') {
        return `${value}%`;
    }
    if (format === 'number') {
        return new Intl.NumberFormat('en-US').format(value);
    }
    return String(value);
}

export interface KpiWidgetProps {
    organizationId: string;
}

export function KpiWidget({ organizationId }: KpiWidgetProps) {
    const [kpis, setKpis] = useState<DashboardKpiItem[] | null>(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let cancelled = false;

        const load = async () => {
            try {
                setLoading(true);
                const response = await axios.get<{ data: DashboardKpiItem[] }>(
                    route('main.dashboard.kpis', { organizationId }),
                );
                if (!cancelled) {
                    setKpis(response.data.data ?? []);
                }
            } catch {
                if (!cancelled) {
                    setKpis(null);
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

    if (!kpis || kpis.length === 0) {
        return <p className="py-8 text-center text-gray-500">No KPI data available</p>;
    }

    return (
        <div className="grid grid-cols-2 gap-4">
            {kpis.map((kpi) => (
                <div key={kpi.name} className="text-center">
                    <div className="text-2xl font-bold text-gray-900">
                        {formatValue(kpi.value, kpi.format)}
                    </div>
                    <div className="mt-1 text-sm text-gray-600">{kpi.name}</div>
                    {kpi.change !== undefined && kpi.change !== null ? (
                        <div
                            className={`mt-1 text-xs ${
                                kpi.change > 0 ? 'text-green-600' : 'text-red-600'
                            }`}
                        >
                            {kpi.change > 0 ? '+' : ''}
                            {kpi.change}%
                        </div>
                    ) : null}
                </div>
            ))}
        </div>
    );
}
