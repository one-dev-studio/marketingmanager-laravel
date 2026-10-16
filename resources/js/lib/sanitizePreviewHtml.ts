import DOMPurify from 'dompurify';

/**
 * Sanitize untrusted builder preview HTML before rendering in the DOM.
 */
export function sanitizePreviewHtml(html: string): string {
    return DOMPurify.sanitize(html, {
        USE_PROFILES: { html: true },
    });
}
