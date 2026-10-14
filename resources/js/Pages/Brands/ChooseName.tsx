import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { useCallback, useState } from 'react';
import { route } from 'ziggy-js';

interface BrandNameSuggestion {
    id: number;
    suggested_name: string;
    description: string | null;
    domain_available: boolean | string | null;
    social_handles: Record<string, unknown> | null;
    domain_check?: string;
    handles?: Record<string, { handle: string; available: boolean }>;
}

export interface BrandsChooseNameProps {
    organizationId: string;
    suggestions: BrandNameSuggestion[];
}

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

export default function ChooseName({ organizationId, suggestions: initialSuggestions }: BrandsChooseNameProps) {
    const [keywordInput, setKeywordInput] = useState('');
    const [count, setCount] = useState(10);
    const [generating, setGenerating] = useState(false);
    const [error, setError] = useState('');
    const [suggestions, setSuggestions] = useState<BrandNameSuggestion[]>(initialSuggestions);

    const generate = useCallback(async () => {
        setGenerating(true);
        setError('');
        try {
            const keywords = keywordInput
                .split(',')
                .map((k) => k.trim())
                .filter(Boolean);
            if (keywords.length === 0) {
                throw new Error('Enter at least one keyword.');
            }
            const response = await fetch(
                route('main.brands.name-generator.generate', { organizationId }),
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    body: JSON.stringify({ keywords, count: Number(count) || 10 }),
                },
            );
            const payload = (await response.json()) as { data?: BrandNameSuggestion[]; message?: string };
            if (!response.ok) {
                throw new Error(payload.message ?? 'Could not generate names.');
            }
            setSuggestions(payload.data ?? []);
        } catch (e) {
            setError(e instanceof Error ? e.message : 'Could not generate names.');
        } finally {
            setGenerating(false);
        }
    }, [count, keywordInput, organizationId]);

    const checkDomain = async (item: BrandNameSuggestion) => {
        const response = await fetch(route('main.brands.name-generator.check-domain', { organizationId }), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({ domain: `${item.suggested_name}.com` }),
        });
        const payload = (await response.json()) as { data?: { available?: boolean } };
        const idx = suggestions.findIndex((s) => s.suggested_name === item.suggested_name);
        if (idx === -1) {
            return;
        }
        const next = [...suggestions];
        next[idx] = { ...next[idx], domain_available: payload.data?.available ?? null };
        setSuggestions(next);
    };

    const checkHandles = async (item: BrandNameSuggestion) => {
        const response = await fetch(route('main.brands.name-generator.check-handles', { organizationId }), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({ name: item.suggested_name }),
        });
        const payload = (await response.json()) as {
            data?: Record<string, { handle: string; available: boolean }>;
        };
        const idx = suggestions.findIndex((s) => s.suggested_name === item.suggested_name);
        if (idx === -1) {
            return;
        }
        const next = [...suggestions];
        next[idx] = { ...next[idx], handles: payload.data ?? {} };
        setSuggestions(next);
    };

    const domainLabel = (item: BrandNameSuggestion): string => {
        const v = item.domain_available;
        if (v === true || v === 'yes') {
            return 'likely available';
        }
        if (v === false || v === 'no') {
            return 'likely taken';
        }
        return item.domain_check ?? 'not checked';
    };

    const createUrl = route('main.brands.create', { organizationId });

    return (
        <AppLayout title="Brand name generator">
            <div className="space-y-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Brand name generator</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Suggest names from keywords, then check .com and social handles. Availability is a
                            heuristic HTTP check, not a registrar or official platform API.
                        </p>
                    </div>
                    <Link
                        href={route('main.brands.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to brands
                    </Link>
                </div>

                <div className="max-w-xl space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">Keywords</label>
                        <input
                            type="text"
                            value={keywordInput}
                            onChange={(e) => setKeywordInput(e.target.value)}
                            placeholder="eco, coffee, roast"
                            className="w-full rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        />
                    </div>
                    <div>
                        <label className="mb-1 block text-sm font-medium text-gray-700">How many names</label>
                        <input
                            type="number"
                            min={1}
                            max={20}
                            value={count}
                            onChange={(e) => setCount(Number(e.target.value))}
                            className="w-32 rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        />
                    </div>
                    <Button type="button" onClick={() => void generate()} disabled={generating}>
                        {generating ? 'Generating…' : 'Generate names'}
                    </Button>
                    {error ? <p className="text-sm text-red-600">{error}</p> : null}
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    {suggestions.map((item) => (
                        <div
                            key={item.id ?? item.suggested_name}
                            className="space-y-3 rounded-lg border border-gray-200 bg-white p-4"
                        >
                            <h2 className="font-medium text-gray-900">{item.suggested_name}</h2>
                            {item.description ? <p className="text-sm text-gray-600">{item.description}</p> : null}
                            <p className="text-sm text-gray-500">Domain: {domainLabel(item)}</p>
                            <div className="flex flex-wrap gap-2">
                                <button
                                    type="button"
                                    className="text-sm text-primary-600 hover:text-primary-800"
                                    onClick={() => void checkDomain(item)}
                                >
                                    Check domain
                                </button>
                                <button
                                    type="button"
                                    className="text-sm text-primary-600 hover:text-primary-800"
                                    onClick={() => void checkHandles(item)}
                                >
                                    Check handles
                                </button>
                                <Link
                                    href={`${createUrl}?name=${encodeURIComponent(item.suggested_name)}`}
                                    className="text-sm text-primary-600 hover:text-primary-800"
                                >
                                    Use this name
                                </Link>
                            </div>
                            {item.handles ? (
                                <ul className="space-y-1 text-sm text-gray-600">
                                    {Object.entries(item.handles).map(([platform, info]) => (
                                        <li key={platform}>
                                            <span className="capitalize">{platform}</span>: {info.handle} —{' '}
                                            {info.available ? 'likely available' : 'likely taken'}
                                        </li>
                                    ))}
                                </ul>
                            ) : null}
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
