import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

export interface ToolsSeoAnalysisProps {
    organizationId: string;
}

export default function SeoAnalysis({ organizationId }: ToolsSeoAnalysisProps) {
    const [url, setUrl] = useState('');
    const [output, setOutput] = useState('');
    const [loading, setLoading] = useState(false);

    const runAnalysis = async () => {
        if (!url.trim()) {
            return;
        }
        setLoading(true);
        setOutput('');
        try {
            const { data } = await axios.post(
                route('main.ai.seo.analyze-content', { organizationId }),
                { url, content: 'Page content to analyze' },
            );
            setOutput(JSON.stringify(data.data ?? data, null, 2));
        } catch (error: unknown) {
            const message =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Analysis failed.';
            setOutput(message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title="SEO Analysis">
            <div className="max-w-3xl space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">SEO Analysis</h1>
                <div className="space-y-3 rounded-lg border border-gray-200 bg-white p-6">
                    <input
                        value={url}
                        type="url"
                        placeholder="https://example.com/page"
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        onChange={(event) => setUrl(event.target.value)}
                    />
                    <Button type="button" disabled={loading} onClick={() => void runAnalysis()}>
                        {loading ? 'Analyzing…' : 'Analyze'}
                    </Button>
                </div>
                {output ? (
                    <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-800">
                        {output}
                    </pre>
                ) : null}
            </div>
        </AppLayout>
    );
}
