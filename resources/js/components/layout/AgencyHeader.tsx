import type { AgencyShellContext } from '@/types/agencyShell';
import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

interface AgencyHeaderProps {
    title: string;
    shell: AgencyShellContext;
    onMobileMenuToggle?: () => void;
    mobileNavOpen?: boolean;
}

export function AgencyHeader({ title, shell, onMobileMenuToggle, mobileNavOpen }: AgencyHeaderProps): ReactElement {
    return (
        <header className="sticky top-0 z-20 border-b border-gray-200 bg-white">
            <div className="flex items-center justify-between px-4 py-4 md:px-6">
                <div className="flex items-center gap-3">
                    <button
                        type="button"
                        onClick={onMobileMenuToggle}
                        className="rounded-lg p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 md:hidden"
                        aria-label="Toggle menu"
                        aria-expanded={mobileNavOpen ?? false}
                        aria-controls="agency-navigation"
                    >
                        <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 className="text-lg font-semibold text-gray-900 md:text-xl">{title}</h1>
                        <p className="text-sm text-gray-500">{shell.agency.name}</p>
                    </div>
                </div>
                <div className="flex items-center gap-2">
                    <Link
                        href={shell.profileHref}
                        className="rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                        {shell.userName}
                    </Link>
                </div>
            </div>
        </header>
    );
}
