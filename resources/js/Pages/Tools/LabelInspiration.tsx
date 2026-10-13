import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

interface SelectOption {
    id: number;
    name: string;
}

export interface ToolsLabelInspirationProps {
    organizationId: string;
    brands: SelectOption[];
    products: SelectOption[];
}

export default function LabelInspiration({ organizationId, brands, products }: ToolsLabelInspirationProps) {
    const [brandId, setBrandId] = useState('');
    const [productId, setProductId] = useState('');
    const [context, setContext] = useState('');
    const [variationCount, setVariationCount] = useState(5);
    const [labels, setLabels] = useState<string[]>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const generate = async () => {
        setLoading(true);
        setError(null);
        setLabels([]);
        try {
            const { data } = await axios.post(route('main.ai.label-inspiration.generate', { organizationId }), {
                brand_id: brandId || null,
                product_id: productId || null,
                context,
                variation_count: variationCount,
            });
            setLabels(data.data?.labels ?? []);
        } catch (err: unknown) {
            const message =
                axios.isAxiosError(err) && err.response?.data?.message
                    ? String(err.response.data.message)
                    : 'Failed to generate labels.';
            setError(message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title="Label Inspiration">
            <div className="grid gap-6 lg:grid-cols-3">
                <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <h1 className="text-lg font-medium text-gray-900">Generate labels</h1>
                    <select
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={brandId}
                        onChange={(e) => setBrandId(e.target.value)}
                    >
                        <option value="">Brand (optional)</option>
                        {brands.map((brand) => (
                            <option key={brand.id} value={brand.id}>{brand.name}</option>
                        ))}
                    </select>
                    <select
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={productId}
                        onChange={(e) => setProductId(e.target.value)}
                    >
                        <option value="">Product (optional)</option>
                        {products.map((product) => (
                            <option key={product.id} value={product.id}>{product.name}</option>
                        ))}
                    </select>
                    <textarea
                        rows={3}
                        placeholder="Additional context"
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={context}
                        onChange={(e) => setContext(e.target.value)}
                    />
                    <input
                        type="number"
                        min={3}
                        max={10}
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={variationCount}
                        onChange={(e) => setVariationCount(Number(e.target.value))}
                    />
                    <Button type="button" disabled={loading} onClick={() => void generate()}>
                        {loading ? 'Generating…' : 'Generate labels'}
                    </Button>
                    {error ? <p className="text-sm text-red-600">{error}</p> : null}
                </div>
                <div className="lg:col-span-2 rounded-lg border border-gray-200 bg-white p-6">
                    <h2 className="mb-4 text-lg font-medium text-gray-900">Results</h2>
                    {labels.length === 0 ? (
                        <p className="text-sm text-gray-500">No labels yet.</p>
                    ) : (
                        <ul className="grid gap-3 md:grid-cols-2">
                            {labels.map((label, index) => (
                                <li key={index} className="rounded-md border border-gray-200 p-3 text-sm text-gray-900">
                                    {label}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
