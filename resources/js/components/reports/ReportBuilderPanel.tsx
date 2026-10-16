import { Button } from '@/components/ui/button';
import type { ReportBuilderData, ReportChartType, ReportWidget } from '@/types/reports';
import axios from 'axios';
import { Chart } from 'chart.js/auto';
import { useEffect, useRef, useState } from 'react';
import { route } from 'ziggy-js';

const REPORT_TYPES = ['overall', 'campaign', 'email', 'social_media', 'custom'] as const;

export interface ReportBuilderPanelProps {
    organizationId: string;
    report: ReportBuilderData;
}

export function ReportBuilderPanel({ organizationId, report }: ReportBuilderPanelProps) {
    const [name, setName] = useState(report.name);
    const [type, setType] = useState(report.type);
    const [widgets, setWidgets] = useState<ReportWidget[]>(report.config?.widgets ?? []);
    const [saving, setSaving] = useState(false);
    const canvasRef = useRef<HTMLCanvasElement | null>(null);
    const chartRef = useRef<Chart | null>(null);

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) {
            return;
        }
        chartRef.current?.destroy();
        const chartType = widgets[0]?.type ?? 'bar';
        chartRef.current = new Chart(canvas, {
            type: chartType,
            data: {
                labels: ['Impressions', 'Clicks', 'Conversions'],
                datasets: [
                    {
                        data: [120, 40, 12],
                        backgroundColor: ['#93c5fd', '#60a5fa', '#2563eb'],
                    },
                ],
            },
        });
        return () => {
            chartRef.current?.destroy();
        };
    }, [widgets]);

    const addChart = (chartType: ReportChartType) => {
        setWidgets((prev) => [...prev, { type: chartType }]);
    };

    const save = async () => {
        if (!report.can.update) {
            return;
        }
        setSaving(true);
        const payload = { name, type, config: { widgets } };
        try {
            if (report.id) {
                await axios.put(
                    route('main.reports.update', { organizationId, reportId: report.id }),
                    payload,
                );
            } else {
                await axios.post(route('main.reports.store', { organizationId }), payload);
            }
            window.location.href = route('main.reports.index', { organizationId });
        } catch {
            setSaving(false);
        }
    };

    const chartTypes: ReportChartType[] = ['bar', 'line', 'pie'];

    return (
        <div className="grid gap-4 md:grid-cols-2">
            <form
                className="space-y-3 rounded-lg border border-gray-200 bg-white p-4"
                onSubmit={(event) => {
                    event.preventDefault();
                    void save();
                }}
            >
                <input
                    value={name}
                    required
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Report name"
                    onChange={(event) => setName(event.target.value)}
                    disabled={!report.can.update}
                />
                <select
                    value={type}
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setType(event.target.value)}
                    disabled={!report.can.update}
                >
                    {REPORT_TYPES.map((option) => (
                        <option key={option} value={option}>
                            {option.replace('_', ' ')}
                        </option>
                    ))}
                </select>
                <div className="flex flex-wrap gap-2">
                    {chartTypes.map((chartType) => (
                        <button
                            key={chartType}
                            type="button"
                            className="rounded border border-gray-300 px-2 py-1 text-sm capitalize hover:bg-gray-50"
                            onClick={() => addChart(chartType)}
                            disabled={!report.can.update}
                        >
                            {chartType}
                        </button>
                    ))}
                </div>
                {widgets.map((widget, index) => (
                    <div key={`${widget.type}-${index}`} className="rounded border border-gray-200 p-2 text-sm">
                        {widget.type} chart
                    </div>
                ))}
                {report.can.update ? (
                    <Button type="submit" disabled={saving}>
                        {saving ? 'Saving…' : 'Save'}
                    </Button>
                ) : null}
            </form>
            <div className="rounded-lg border border-gray-200 bg-white p-4">
                <canvas ref={canvasRef} />
            </div>
        </div>
    );
}
