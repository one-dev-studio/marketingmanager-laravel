import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

export interface IntelligenceSentimentProps {
    organizationId: string;
}

export default function Sentiment({ organizationId }: IntelligenceSentimentProps) {
    const [text, setText] = useState('');
    const [output, setOutput] = useState('');
    const [loading, setLoading] = useState(false);

    const runAnalysis = async () => {
        if (!text.trim()) {
            return;
        }
        setLoading(true);
        setOutput('');
        try {
            const { data } = await axios.post(
                route('main.analytics.sentiment.analyze', { organizationId }),
                { text },
            );
            setOutput(JSON.stringify(data.data ?? data, null, 2));
        } catch (error: unknown) {
            const message =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Sentiment analysis failed.';
            setOutput(message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title="Sentiment">
            <div className="space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">Sentiment</h1>
                <textarea
                    value={text}
                    rows={5}
                    placeholder="Text or paste a URL"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setText(event.target.value)}
                />
                <Button type="button" disabled={loading} onClick={() => void runAnalysis()}>
                    {loading ? 'Analyzing…' : 'Analyze'}
                </Button>
                {output ? (
                    <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm">
                        {output}
                    </pre>
                ) : null}
            </div>
        </AppLayout>
    );
}
