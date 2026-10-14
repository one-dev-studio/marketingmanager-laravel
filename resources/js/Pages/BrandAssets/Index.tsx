import AppLayout from '@/Layouts/AppLayout';

interface BrandAssetItem {
    id: number;
    name: string;
    type: string;
    url: string | null;
    tags: string[] | null;
    created_at: string;
}

interface BrandGuidelines {
    guidelines: string | null;
    tone_of_voice: string | null;
    keywords: string[];
    avoid_keywords: string[];
    audience: string | null;
    summary: string | null;
    logo_url: string | null;
}

interface AssetsGrouped {
    logo: BrandAssetItem[];
    image: BrandAssetItem[];
    font: BrandAssetItem[];
    color: BrandAssetItem[];
    other: BrandAssetItem[];
}

export interface BrandAssetsIndexProps {
    organizationId: string;
    brandId: string;
    brandName: string;
    guidelines: BrandGuidelines;
    assetsGrouped: AssetsGrouped;
}

function AssetSection({ title, items }: { title: string; items: BrandAssetItem[] }) {
    if (items.length === 0) {
        return null;
    }

    return (
        <div className="mb-6">
            <h3 className="mb-2 text-sm font-medium text-gray-700">{title}</h3>
            <ul className="space-y-2">
                {items.map((asset) => (
                    <li key={asset.id} className="rounded border border-gray-100 p-2 text-sm">
                        <p className="font-medium text-gray-900">{asset.name}</p>
                        {asset.url && (
                            <a href={asset.url} className="text-primary-600 hover:underline" target="_blank" rel="noreferrer">
                                View
                            </a>
                        )}
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default function Index({ brandName, guidelines, assetsGrouped }: BrandAssetsIndexProps) {
    return (
        <AppLayout title="Brand Assets">
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">{brandName}</h1>
                    <p className="mt-1 text-sm text-gray-600">Brand guidelines and visual assets</p>
                </div>

                <div className="grid gap-6 lg:grid-cols-3">
                    <div className="rounded-lg border border-gray-200 bg-white p-6 lg:col-span-1">
                        <h2 className="mb-4 text-lg font-medium text-gray-900">Brand guidelines</h2>
                        {guidelines.logo_url && (
                            <div className="mb-4">
                                <img
                                    src={guidelines.logo_url}
                                    alt={`${brandName} logo`}
                                    className="max-h-32 max-w-full object-contain"
                                />
                            </div>
                        )}
                        {guidelines.summary && (
                            <p className="mb-3 text-sm text-gray-600">{guidelines.summary}</p>
                        )}
                        {guidelines.tone_of_voice && (
                            <p className="mb-3 text-sm text-gray-600">
                                <span className="font-medium text-gray-700">Tone:</span> {guidelines.tone_of_voice}
                            </p>
                        )}
                        {guidelines.audience && (
                            <p className="mb-3 text-sm text-gray-600">
                                <span className="font-medium text-gray-700">Audience:</span> {guidelines.audience}
                            </p>
                        )}
                        {guidelines.guidelines && (
                            <p className="whitespace-pre-wrap text-sm text-gray-600">{guidelines.guidelines}</p>
                        )}
                        {guidelines.keywords.length > 0 && (
                            <div className="mt-4 flex flex-wrap gap-2">
                                {guidelines.keywords.map((keyword) => (
                                    <span
                                        key={keyword}
                                        className="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700"
                                    >
                                        {keyword}
                                    </span>
                                ))}
                            </div>
                        )}
                    </div>

                    <div className="rounded-lg border border-gray-200 bg-white p-6 lg:col-span-2">
                        <h2 className="mb-4 text-lg font-medium text-gray-900">Assets</h2>
                        <AssetSection title="Logos" items={assetsGrouped.logo} />
                        <AssetSection title="Images" items={assetsGrouped.image} />
                        <AssetSection title="Fonts" items={assetsGrouped.font} />
                        <AssetSection title="Colors" items={assetsGrouped.color} />
                        <AssetSection title="Other" items={assetsGrouped.other} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
