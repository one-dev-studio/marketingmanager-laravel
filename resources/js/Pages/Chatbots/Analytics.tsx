import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface ChatbotSummary {
    id: number;
    name: string;
}

export interface ChatbotsAnalyticsProps {
    organizationId: string;
    chatbot: ChatbotSummary;
    analytics: Record<string, unknown>;
}

export default function Analytics({ organizationId, chatbot, analytics }: ChatbotsAnalyticsProps) {
    return (
        <AppLayout title={`Analytics — ${chatbot.name}`}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{chatbot.name}</h1>
                        <p className="mt-1 text-sm text-gray-600">Chatbot performance (last 30 days)</p>
                    </div>
                    <Link
                        href={route('main.chatbots.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <MetricCard label="Conversations" value={analytics.total_conversations} />
                    <MetricCard label="Leads" value={analytics.total_leads} />
                    <MetricCard label="Avg. duration (s)" value={analytics.average_conversation_duration} />
                    <MetricCard label="Conversion %" value={analytics.conversion_rate} />
                </div>

                <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-xs">
                    {JSON.stringify(analytics, null, 2)}
                </pre>
            </div>
        </AppLayout>
    );
}

function MetricCard({ label, value }: { label: string; value: unknown }) {
    const display =
        typeof value === 'number' ? (Number.isInteger(value) ? String(value) : value.toFixed(1)) : String(value ?? '—');

    return (
        <div className="rounded-lg border border-gray-200 bg-white p-4">
            <p className="text-sm text-gray-600">{label}</p>
            <p className="mt-1 text-2xl font-semibold text-gray-900">{display}</p>
        </div>
    );
}
