import { BuilderHtmlPreview } from '@/components/builders/BuilderHtmlPreview';
import type { LandingPageBlock, LandingPageBlockType, LandingPageBuilderData } from '@/types/landing-pages';
import { Button } from '@/components/ui/button';
import axios from 'axios';
import { useMemo, useState } from 'react';
import { route } from 'ziggy-js';

function blocksFromPage(page: LandingPageBuilderData): LandingPageBlock[] {
    const raw = page.page_data?.blocks;
    if (!Array.isArray(raw)) {
        return [];
    }
    return raw.map((block) => ({
        type: block.type as LandingPageBlockType,
        value: block.value ?? '',
    }));
}

function renderPreviewHtml(blocks: LandingPageBlock[]): string {
    return blocks
        .map((block) => {
            if (block.type === 'hero') {
                return `<section class="p-8 bg-slate-900 text-white text-3xl">${block.value}</section>`;
            }
            if (block.type === 'cta') {
                return `<p><a class="inline-block bg-blue-600 text-white px-4 py-2 rounded">${block.value || 'Get started'}</a></p>`;
            }
            if (block.type === 'form') {
                return '<form class="space-y-2"><input class="border p-2 w-full" placeholder="Email"><button class="bg-blue-600 text-white px-3 py-1">Submit</button></form>';
            }
            return `<p>${block.value || ''}</p>`;
        })
        .join('');
}

export interface LandingPageBuilderPanelProps {
    organizationId: string;
    page: LandingPageBuilderData;
}

export function LandingPageBuilderPanel({ organizationId, page }: LandingPageBuilderPanelProps) {
    const [blocks, setBlocks] = useState<LandingPageBlock[]>(() => blocksFromPage(page));
    const [seoTitle, setSeoTitle] = useState(page.seo_settings?.title ?? '');
    const [seoDesc, setSeoDesc] = useState(page.seo_settings?.description ?? '');
    const [domain, setDomain] = useState(page.custom_domain ?? '');
    const [saving, setSaving] = useState(false);
    const [saveMessage, setSaveMessage] = useState<string | null>(null);

    const previewHtml = useMemo(() => renderPreviewHtml(blocks), [blocks]);

    const addBlock = (type: LandingPageBlockType) => {
        setBlocks((current) => [...current, { type, value: '' }]);
    };

    const updateBlock = (index: number, value: string) => {
        setBlocks((current) =>
            current.map((block, i) => (i === index ? { ...block, value } : block)),
        );
    };

    const save = async () => {
        if (!page.can.update) {
            return;
        }
        setSaving(true);
        setSaveMessage(null);
        try {
            await axios.put(
                route('main.landing-pages.update', {
                    organizationId,
                    landingPage: page.id,
                }),
                {
                    html_content: previewHtml,
                    page_data: { blocks },
                    seo_settings: { title: seoTitle, description: seoDesc },
                    custom_domain: domain || null,
                },
            );
            setSaveMessage('Saved');
        } catch {
            setSaveMessage('Save failed — check your connection and try again.');
        } finally {
            setSaving(false);
        }
    };

    return (
        <div className="grid gap-4 md:grid-cols-2">
            <div className="space-y-3 rounded-lg border border-gray-200 bg-white p-4">
                <div className="flex flex-wrap gap-2">
                    {(['hero', 'text', 'cta', 'form'] as LandingPageBlockType[]).map((type) => (
                        <button
                            key={type}
                            type="button"
                            className="rounded border border-gray-300 px-2 py-1 text-sm hover:bg-gray-50"
                            onClick={() => addBlock(type)}
                        >
                            {type.charAt(0).toUpperCase() + type.slice(1)}
                        </button>
                    ))}
                </div>
                {blocks.map((block, index) => (
                    <div key={`${block.type}-${index}`} className="rounded border border-gray-200 p-2">
                        <label className="mb-1 block text-xs font-medium uppercase text-gray-500">
                            {block.type}
                        </label>
                        <input
                            value={block.value}
                            className="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                            onChange={(event) => updateBlock(index, event.target.value)}
                        />
                    </div>
                ))}
                <input
                    value={seoTitle}
                    className="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                    placeholder="SEO title"
                    onChange={(event) => setSeoTitle(event.target.value)}
                />
                <input
                    value={seoDesc}
                    className="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                    placeholder="SEO description"
                    onChange={(event) => setSeoDesc(event.target.value)}
                />
                <input
                    value={domain}
                    className="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                    placeholder="Custom domain"
                    onChange={(event) => setDomain(event.target.value)}
                />
                <div className="flex items-center gap-3">
                    <Button type="button" disabled={saving || !page.can.update} onClick={() => void save()}>
                        {saving ? 'Saving…' : 'Save'}
                    </Button>
                    {saveMessage ? <span className="text-sm text-gray-600">{saveMessage}</span> : null}
                </div>
            </div>
            <div className="rounded-lg border border-gray-200 bg-white p-4">
                <BuilderHtmlPreview html={previewHtml} className="prose max-w-none" />
            </div>
        </div>
    );
}
