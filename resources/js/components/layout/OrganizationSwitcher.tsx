import type { AppShellContext } from '@/types/appShell';
import { Link } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

export function OrganizationSwitcher({ shell }: { shell: AppShellContext }): ReactElement {
    const [open, setOpen] = useState(false);

    return (
        <div className="relative">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="flex items-center space-x-2 rounded-lg px-3 py-2 text-gray-700 hover:bg-gray-100"
            >
                <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                    />
                </svg>
                <span className="hidden md:block">{shell.currentOrganization.name}</span>
                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {open ? (
                <div className="absolute right-0 z-50 mt-2 w-56 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                    {shell.organizations.map((org) => (
                        <Link
                            key={org.id}
                            href={org.href}
                            className={`block px-4 py-2 text-sm hover:bg-gray-100 ${
                                org.id === shell.currentOrganization.id
                                    ? 'bg-primary-50 text-primary-700'
                                    : 'text-gray-700'
                            }`}
                            onClick={() => setOpen(false)}
                        >
                            {org.name}
                        </Link>
                    ))}
                </div>
            ) : null}
        </div>
    );
}
