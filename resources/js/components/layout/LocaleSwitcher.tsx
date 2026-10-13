import type { AppShellContext } from '@/types/appShell';
import { Link } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

export function LocaleSwitcher({ shell }: { shell: AppShellContext }): ReactElement {
    const [open, setOpen] = useState(false);

    return (
        <div className="relative">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
            >
                {shell.currentLocale.toUpperCase()}
            </button>
            {open ? (
                <div className="absolute right-0 z-50 mt-2 w-56 rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                    {shell.locales.map((locale) => (
                        <Link
                            key={locale.code}
                            href={locale.href}
                            className={`block px-4 py-2 text-sm ${
                                locale.isCurrent
                                    ? 'bg-gray-100 font-semibold text-gray-900'
                                    : 'text-gray-700 hover:bg-gray-50'
                            }`}
                            onClick={() => setOpen(false)}
                        >
                            {locale.name}
                        </Link>
                    ))}
                </div>
            ) : null}
        </div>
    );
}
