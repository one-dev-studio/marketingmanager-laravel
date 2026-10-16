import { InputError } from '@/components/InputError';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { BrandDetail } from '@/types/brands';

export interface BrandFormData {
    name: string;
    summary: string;
    audience: string;
    guidelines: string;
    tone_of_voice: string;
    keywords: string;
    avoid_keywords: string;
    status: string;
    business_model: string;
    logo: File | null;
}

export function brandFormDefaults(
    brand: BrandDetail | null,
    prefillName: string | null,
): BrandFormData {
    return {
        name: brand?.name ?? prefillName ?? '',
        summary: brand?.summary ?? '',
        audience: brand?.audience ?? '',
        guidelines: brand?.guidelines ?? '',
        tone_of_voice: brand?.tone_of_voice ?? '',
        keywords: brand?.keywords?.join(', ') ?? '',
        avoid_keywords: brand?.avoid_keywords?.join(', ') ?? '',
        status: brand?.status ?? 'active',
        business_model: brand?.business_model ?? '',
        logo: null,
    };
}

interface BrandFormFieldsProps {
    data: BrandFormData;
    errors: Record<string, string | undefined>;
    setData: (key: keyof BrandFormData, value: BrandFormData[keyof BrandFormData]) => void;
}

export function BrandFormFields({ data, errors, setData }: BrandFormFieldsProps) {
    return (
        <div className="space-y-4">
            <div>
                <Label htmlFor="name">Brand name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    maxLength={255}
                    className="mt-1"
                />
                <InputError message={errors.name} />
            </div>
            <div>
                <Label htmlFor="summary">Summary</Label>
                <textarea
                    id="summary"
                    rows={3}
                    maxLength={1000}
                    value={data.summary}
                    onChange={(e) => setData('summary', e.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                />
                <InputError message={errors.summary} />
            </div>
            <div>
                <Label htmlFor="guidelines">Guidelines</Label>
                <textarea
                    id="guidelines"
                    rows={5}
                    value={data.guidelines}
                    onChange={(e) => setData('guidelines', e.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                />
                <InputError message={errors.guidelines} />
            </div>
            <div>
                <Label htmlFor="tone_of_voice">Tone of voice</Label>
                <Input
                    id="tone_of_voice"
                    value={data.tone_of_voice}
                    onChange={(e) => setData('tone_of_voice', e.target.value)}
                    maxLength={255}
                    className="mt-1"
                />
                <InputError message={errors.tone_of_voice} />
            </div>
            <div>
                <Label htmlFor="audience">Target audience</Label>
                <textarea
                    id="audience"
                    rows={3}
                    value={data.audience}
                    onChange={(e) => setData('audience', e.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                />
                <InputError message={errors.audience} />
            </div>
            <div>
                <Label htmlFor="keywords">Keywords to use</Label>
                <Input
                    id="keywords"
                    value={data.keywords}
                    onChange={(e) => setData('keywords', e.target.value)}
                    placeholder="comma-separated"
                    className="mt-1"
                />
                <InputError message={errors.keywords} />
            </div>
            <div>
                <Label htmlFor="avoid_keywords">Keywords to avoid</Label>
                <Input
                    id="avoid_keywords"
                    value={data.avoid_keywords}
                    onChange={(e) => setData('avoid_keywords', e.target.value)}
                    placeholder="comma-separated"
                    className="mt-1"
                />
                <InputError message={errors.avoid_keywords} />
            </div>
            <div>
                <Label htmlFor="status">Status</Label>
                <select
                    id="status"
                    value={data.status}
                    onChange={(e) => setData('status', e.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                    <option value="active">active</option>
                    <option value="inactive">inactive</option>
                </select>
                <InputError message={errors.status} />
            </div>
            <div>
                <Label htmlFor="logo">Logo (optional)</Label>
                <Input
                    id="logo"
                    type="file"
                    accept="image/*"
                    onChange={(e) => setData('logo', e.target.files?.[0] ?? null)}
                    className="mt-1"
                />
                <InputError message={errors.logo} />
            </div>
        </div>
    );
}
