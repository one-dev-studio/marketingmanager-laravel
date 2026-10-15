import '../bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME ?? 'Marketing Manager';

/** Lazy-loaded Inertia pages (separate chunks per route). */
const inertiaPages = import.meta.glob('../Pages/**/*.tsx');

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`../Pages/${name}.tsx`, inertiaPages),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#2563eb',
    },
});
