import { Button } from '@/components/ui/button';
import type { EmailBlock, EmailBlockType, EmailTemplateBuilderData } from '@/types/email-templates';
import axios from 'axios';
import { useMemo, useState } from 'react';
import { route } from 'ziggy-js';

function blocksFromTemplate(template: EmailTemplateBuilderData): EmailBlock[] {
    if (template.html_content) {
        return [{ type: 'text', value: template.html_content }];
    }
    return [];
}

function renderPreviewHtml(blocks: EmailBlock[]): string {
    return blocks
        .map((block) => {
            if (block.type === 'heading') {
                return `<h2>${block.value || ''}</h2>`;
            }
            if (block.type === 'button') {
                return `<p><a href="#" style="background:#2563eb;color:#fff;padding:8px 12px;border-radius:6px">${block.value || 'Click'}</a></p>`;
            }
            if (block.type === 'image') {
                return `<p><img src="${block.value || ''}" alt="" style="max-width:100%"></p>`;
            }
            return `<p>${(block.value || '').replace(/\n/g, '<br>')}</p>`;
        })
        .join('');
}

export interface EmailTemplateBuilderPanelProps {
    organizationId: string;
    template: EmailTemplateBuilderData;
}

export function EmailTemplateBuilderPanel({ organizationId, template }: EmailTemplateBuilderPanelProps) {
    const [formName, setFormName] = useState(template.name);
    const [formSubject, setFormSubject] = useState(template.subject);
    const [blocks, setBlocks] = useState<EmailBlock[]>(() => blocksFromTemplate(template));
    const [saving, setSaving] = useState(false);
    const [saveMessage, setSaveMessage] = useState<string | null>(null);

    const previewHtml = useMemo(() => renderPreviewHtml(blocks), [blocks]);

    const addBlock = (type: EmailBlockType) => {
        setBlocks((prev) => [...prev, { type, value: '' }]);
    };

    const updateBlock = (index: number, value: string) => {
        setBlocks((prev) => prev.map((b, i) => (i === index ? { ...b, value } : b)));
    };

    const removeBlock = (index: number) => {
        setBlocks((prev) => prev.filter((_, i) => i !== index));
    };

    const save = async () => {
        if (!template.can.update) {
            return;
        }
        setSaving(true);
        setSaveMessage(null);
        const payload = {
            name: formName,
            subject: formSubject,
            html_content: previewHtml,
            text_content: blocks.map((b) => b.value).join('\n'),
        };
        try {
            if (template.id) {
                await axios.put(
                    route('main.email-marketing.templates.update', {
                        organizationId,
                        emailTemplate: template.id,
                    }),
                    payload,
                );
            } else {
                await axios.post(
                    route('main.email-marketing.templates.store', { organizationId }),
                    payload,
                );
            }
            setSaveMessage('Template saved.');
            window.location.href = route('main.email-marketing.templates.index', { organizationId });
        } catch {
            setSaveMessage('Save failed. Check your connection and try again.');
            setSaving(false);
        }
    };

    const blockTypes: EmailBlockType[] = ['heading', 'text', 'button', 'image'];

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
                    value={formName}
                    required
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Name"
                    onChange={(event) => setFormName(event.target.value)}
                    disabled={!template.can.update}
                />
                <input
                    value={formSubject}
                    required
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Subject"
                    onChange={(event) => setFormSubject(event.target.value)}
                    disabled={!template.can.update}
                />
                <div className="flex flex-wrap gap-2">
                    {blockTypes.map((type) => (
                        <button
                            key={type}
                            type="button"
                            className="rounded border border-gray-300 px-2 py-1 text-sm capitalize hover:bg-gray-50"
                            onClick={() => addBlock(type)}
                            disabled={!template.can.update}
                        >
                            {type}
                        </button>
                    ))}
                </div>
                {blocks.map((block, index) => (
                    <div key={`${block.type}-${index}`} className="space-y-1 rounded border border-gray-200 p-2">
                        <div className="flex justify-between text-xs text-gray-500">
                            <span>{block.type}</span>
                            {template.can.update ? (
                                <button type="button" onClick={() => removeBlock(index)}>Remove</button>
                            ) : null}
                        </div>
                        {block.type === 'text' ? (
                            <textarea
                                value={block.value}
                                rows={3}
                                className="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                                onChange={(event) => updateBlock(index, event.target.value)}
                                disabled={!template.can.update}
                            />
                        ) : (
                            <input
                                value={block.value}
                                className="w-full rounded border border-gray-300 px-2 py-1 text-sm"
                                onChange={(event) => updateBlock(index, event.target.value)}
                                disabled={!template.can.update}
                            />
                        )}
                    </div>
                ))}
                {template.can.update ? (
                    <div className="flex items-center gap-3">
                        <Button type="submit" disabled={saving}>
                            {saving ? 'Saving…' : 'Save template'}
                        </Button>
                        {saveMessage ? <span className="text-sm text-gray-600">{saveMessage}</span> : null}
                    </div>
                ) : null}
            </form>
            <div className="rounded-lg border border-gray-200 bg-white p-4">
                <h2 className="mb-2 font-medium text-gray-900">Preview</h2>
                <div className="prose max-w-none" dangerouslySetInnerHTML={{ __html: previewHtml }} />
            </div>
        </div>
    );
}
