import { ShellMobileSidebar } from '@/components/layout/ShellMobileSidebar';
import { SidebarIcon } from '@/components/layout/SidebarIcon';
import type { AgencySidebarNavigation } from '@/types/agencyShell';
import { Link } from '@inertiajs/react';
import type { ReactElement } from 'react';

interface AgencySidebarProps {
    navigation: AgencySidebarNavigation;
    exitAgencyHref: string;
    mobileOpen: boolean;
    onMobileClose: () => void;
}

function AgencySidebarNav({
    navigation,
    exitAgencyHref,
    onNavigate,
}: {
    navigation: AgencySidebarNavigation;
    exitAgencyHref: string;
    onNavigate?: () => void;
}): ReactElement {
    return (
        <>
            <div className="flex h-16 items-center border-b border-gray-200 px-4">
                <svg className="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                    />
                </svg>
                <span className="ml-2 text-lg font-semibold text-gray-900">{navigation.title}</span>
            </div>
            <nav className="flex-1 space-y-1 overflow-y-auto p-3">
                {navigation.items.map((item) => {
                    const activeClasses = item.isActive
                        ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600'
                        : 'text-gray-700 hover:bg-gray-100';

                    return (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={`flex items-center rounded-lg px-3 py-2 transition-colors ${activeClasses}`}
                            aria-current={item.isActive ? 'page' : undefined}
                            onClick={onNavigate}
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
            <div className="border-t border-gray-200 p-3">
                <Link
                    href={exitAgencyHref}
                    className="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100"
                    onClick={onNavigate}
                >
                    Exit Agency View
                </Link>
            </div>
        </>
    );
}

export function AgencySidebar({
    navigation,
    exitAgencyHref,
    mobileOpen,
    onMobileClose,
}: AgencySidebarProps): ReactElement {
    return (
        <>
            <aside className="fixed inset-y-0 left-0 z-30 hidden w-64 flex-col border-r border-gray-200 bg-white md:flex">
                <AgencySidebarNav navigation={navigation} exitAgencyHref={exitAgencyHref} />
            </aside>
            <ShellMobileSidebar
                open={mobileOpen}
                onClose={onMobileClose}
                navLabel="Agency navigation"
                panelId="agency-navigation"
            >
                <AgencySidebarNav
                    navigation={navigation}
                    exitAgencyHref={exitAgencyHref}
                    onNavigate={onMobileClose}
                />
            </ShellMobileSidebar>
        </>
    );
}
