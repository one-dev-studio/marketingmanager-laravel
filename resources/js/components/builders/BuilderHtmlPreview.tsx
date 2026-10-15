import { sanitizePreviewHtml } from '@/lib/sanitizePreviewHtml';
import { useMemo } from 'react';

export interface BuilderHtmlPreviewProps {
    html: string;
    className?: string;
    title?: string;
}

/**
 * Renders builder HTML in a script-less sandbox iframe after client-side sanitization.
 */
export function BuilderHtmlPreview({
    html,
    className = '',
    title = 'Preview',
}: BuilderHtmlPreviewProps) {
    const safeHtml = useMemo(() => sanitizePreviewHtml(html), [html]);

    return (
        <iframe
            title={title}
            sandbox=""
            srcDoc={safeHtml}
            className={`min-h-[240px] w-full border-0 bg-white ${className}`.trim()}
        />
    );
}
