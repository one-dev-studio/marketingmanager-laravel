import type { ReactElement } from 'react';

export function ShellSkipLink(): ReactElement {
    return (
        <a
            href="#main-content"
            className="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-primary-700 focus:shadow-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
        >
            Skip to main content
        </a>
    );
}
