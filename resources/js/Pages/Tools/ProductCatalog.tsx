import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

interface SelectOption {
    id: number;
    name: string;
    brand_name?: string | null;
}

export interface ToolsProductCatalogProps {
    organizationId: string;
    brands: SelectOption[];
    products: SelectOption[];
}

export default function ProductCatalog({ organizationId, brands, products }: ToolsProductCatalogProps) {
    const [brandId, setBrandId] = useState('');
    const [productIds, setProductIds] = useState<number[]>([]);
    const [format, setFormat] = useState('standard');
    const [catalogContent, setCatalogContent] = useState('');
    const [descriptions, setDescriptions] = useState<Array<{ product_name: string; description: string }>>([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const toggleProduct = (id: number) => {
        setProductIds((prev) => (prev.includes(id) ? prev.filter((p) => p !== id) : [...prev, id]));
    };

    const payload = () => ({
        brand_id: brandId || null,
        product_ids: productIds,
        format,
    });

    const generateCatalog = async () => {
        if (productIds.length === 0) {
            setError('Select at least one product.');
            return;
        }
        setLoading(true);
        setError(null);
        setCatalogContent('');
        setDescriptions([]);
        try {
            const { data } = await axios.post(route('main.ai.product-catalog.generate', { organizationId }), payload());
            setCatalogContent(data.data?.generated_content ?? data.data?.content ?? '');
        } catch (err: unknown) {
            setError(axios.isAxiosError(err) ? String(err.response?.data?.message ?? 'Catalog failed.') : 'Catalog failed.');
        } finally {
            setLoading(false);
        }
    };

    const generateDescriptions = async () => {
        if (productIds.length === 0) {
            setError('Select at least one product.');
            return;
        }
        setLoading(true);
        setError(null);
        setCatalogContent('');
        setDescriptions([]);
        try {
            const { data } = await axios.post(
                route('main.ai.product-catalog.descriptions', { organizationId }),
                payload(),
            );
            setDescriptions(Array.isArray(data.data) ? data.data : []);
        } catch (err: unknown) {
            setError(
                axios.isAxiosError(err) ? String(err.response?.data?.message ?? 'Descriptions failed.') : 'Descriptions failed.',
            );
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title="Product Catalog">
            <div className="grid gap-6 lg:grid-cols-3">
                <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <h1 className="text-lg font-medium text-gray-900">Generate catalog</h1>
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
                    <div className="max-h-48 space-y-2 overflow-y-auto rounded-md border border-gray-300 p-2">
                        {products.length === 0 ? (
                            <p className="text-sm text-gray-500">No products yet.</p>
                        ) : (
                            products.map((product) => (
                                <label key={product.id} className="flex cursor-pointer gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        checked={productIds.includes(product.id)}
                                        onChange={() => toggleProduct(product.id)}
                                    />
                                    <span>
                                        {product.name}
                                        {product.brand_name ? (
                                            <span className="block text-xs text-gray-500">{product.brand_name}</span>
                                        ) : null}
                                    </span>
                                </label>
                            ))
                        )}
                    </div>
                    <select
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={format}
                        onChange={(e) => setFormat(e.target.value)}
                    >
                        <option value="standard">Standard</option>
                        <option value="detailed">Detailed</option>
                        <option value="minimal">Minimal</option>
                    </select>
                    <div className="flex flex-col gap-2">
                        <Button type="button" disabled={loading} onClick={() => void generateCatalog()}>
                            Generate catalog
                        </Button>
                        <Button type="button" variant="outline" disabled={loading} onClick={() => void generateDescriptions()}>
                            Descriptions only
                        </Button>
                    </div>
                    {error ? <p className="text-sm text-red-600">{error}</p> : null}
                </div>
                <div className="lg:col-span-2 space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    {catalogContent ? (
                        <pre className="whitespace-pre-wrap rounded-md bg-gray-50 p-4 text-sm text-gray-800">{catalogContent}</pre>
                    ) : null}
                    {descriptions.map((desc, index) => (
                        <div key={index} className="rounded-md border border-gray-200 p-4">
                            <h3 className="font-medium text-gray-900">{desc.product_name}</h3>
                            <p className="mt-1 whitespace-pre-wrap text-sm text-gray-700">{desc.description}</p>
                        </div>
                    ))}
                    {!catalogContent && descriptions.length === 0 ? (
                        <p className="text-sm text-gray-500">No output yet.</p>
                    ) : null}
                </div>
            </div>
        </AppLayout>
    );
}
