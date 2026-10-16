/**
 * Renders Laravel pagination link labels without dangerouslySetInnerHTML.
 */
export function paginationLabelText(label: string): string {
    if (typeof document === 'undefined') {
        return label.replace(/<[^>]*>/g, '');
    }

    const template = document.createElement('template');
    template.innerHTML = label;

    return template.content.textContent?.trim() ?? label;
}
