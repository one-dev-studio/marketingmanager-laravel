import type { AgencyShellContext } from '@/types/agencyShell';
import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

interface AgencyHeaderProps {
    title: string;
    shell: AgencyShellContext;
}

export function AgencyHeader({ title, shell }: AgencyHeaderProps): ReactElement {
    return (
        <header className="sticky top-0 z-20 border-b border-gray-200 bg-white">
            <div className="flex items-center justify-between px-4 py-4 md:px-6">
                <div>
                    <h1 className="text-lg font-semibold text-gray-900 md:text-xl">{title}</h1>
                    <p className="text-sm text-gray-500">{shell.agency.name}</p>
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
