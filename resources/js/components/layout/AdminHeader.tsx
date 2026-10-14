import type { AdminShellContext } from '@/types/adminShell';
import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

interface AdminHeaderProps {
    title: string;
    shell: AdminShellContext;
}

export function AdminHeader({ title, shell }: AdminHeaderProps): ReactElement {
    return (
        <header className="sticky top-0 z-20 border-b border-gray-200 bg-white">
            <div className="flex items-center justify-between px-4 py-4 md:px-6">
                <h1 className="text-lg font-semibold text-gray-900 md:text-xl">{title}</h1>
                <Link
                    href={shell.profileHref}
                    className="rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    {shell.userName}
                </Link>
            </div>
        </header>
    );
}
