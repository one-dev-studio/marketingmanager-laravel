import { SidebarIcon } from '@/components/layout/SidebarIcon';
import type { AdminSidebarNavigation } from '@/types/adminShell';
import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

interface AdminSidebarProps {
    navigation: AdminSidebarNavigation;
    returnToAppHref: string;
    userName: string;
}

export function AdminSidebar({ navigation, returnToAppHref, userName }: AdminSidebarProps): ReactElement {
    return (
        <aside className="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col bg-gray-900 md:flex">
            <div className="flex h-16 items-center border-b border-gray-800 px-4">
                <svg className="h-6 w-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                    />
                </svg>
                <span className="ml-2 text-lg font-semibold text-white">{navigation.title}</span>
            </div>
            <nav className="flex-1 space-y-1 overflow-y-auto p-3">
                {navigation.items.map((item) => {
                    const activeClasses = item.isActive
                        ? 'bg-gray-800 text-white border-r-2 border-primary-400'
                        : 'text-gray-300 hover:bg-gray-800 hover:text-white';

                    return (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={`flex items-center rounded-lg px-3 py-2 transition-colors ${activeClasses}`}
                            aria-current={item.isActive ? 'page' : undefined}
                        >
                            {item.icon ? (
                                <span className="mr-3 flex-shrink-0">
                                    <SidebarIcon name={item.icon} />
                                </span>
                            ) : null}
                            <span className="flex-1">{item.label}</span>
                        </Link>
                    );
                })}
            </nav>
            <div className="border-t border-gray-800 p-3 text-sm">
                <p className="truncate px-3 py-1 text-gray-400">{userName}</p>
                <Link
                    href={returnToAppHref}
                    className="mt-1 block rounded-lg px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white"
                >
                    Return to app
                </Link>
            </div>
        </aside>
    );
}
