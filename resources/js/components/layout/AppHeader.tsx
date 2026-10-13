import { LocaleSwitcher } from '@/components/layout/LocaleSwitcher';
import { OrganizationSwitcher } from '@/components/layout/OrganizationSwitcher';
import type { AppShellContext } from '@/types/appShell';
import { Link } from '@inertiajs/react';
import { useState, type ReactElement } from 'react';

interface AppHeaderProps {
    title: string;
    shell: AppShellContext;
    onMobileMenuToggle?: () => void;
}

function NotificationsMenu({ count }: { count: number }): ReactElement {
    const [open, setOpen] = useState(false);

    return (
        <div className="relative">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="relative rounded-lg p-2 text-gray-600 hover:bg-gray-100"
                aria-label="Notifications"
            >
                <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                    />
                </svg>
                {count > 0 ? (
                    <span className="absolute right-0 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                        {count}
                    </span>
                ) : null}
            </button>
            {open ? (
                <div className="absolute right-0 z-50 mt-2 max-h-96 w-80 overflow-y-auto rounded-lg border border-gray-200 bg-white py-2 shadow-lg">
                    <div className="border-b border-gray-200 px-4 py-2">
                        <h3 className="text-sm font-semibold text-gray-900">Notifications</h3>
                    </div>
                    <p className="px-4 py-8 text-center text-sm text-gray-500">No notifications</p>
                </div>
            ) : null}
        </div>
    );
}

function CalendarShortcut({ href }: { href: string }): ReactElement {
    return (
        <Link
            href={href}
            className="rounded-lg p-2 text-gray-600 hover:bg-gray-100"
            aria-label="Open dashboard calendar"
            title="Calendar"
        >
            <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                />
            </svg>
        </Link>
    );
}

export function AppHeader({ title, shell, onMobileMenuToggle }: AppHeaderProps): ReactElement {
    return (
        <header className="sticky top-0 z-20 border-b border-gray-200 bg-white">
            <div className="flex items-center justify-between px-4 py-4 md:px-6">
                <div className="flex items-center space-x-4">
                    <button
                        type="button"
                        onClick={onMobileMenuToggle}
                        className="rounded-lg p-2 text-gray-600 hover:bg-gray-100 md:hidden"
                        aria-label="Toggle menu"
                    >
                        <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 className="text-xl font-semibold text-gray-900">{title}</h1>
                </div>

                <div className="flex items-center space-x-2 md:space-x-4">
                    <OrganizationSwitcher shell={shell} />
                    <CalendarShortcut href={shell.dashboardHref} />
                    <Link
                        href={shell.reviewHref}
                        className="relative rounded-lg p-2 text-gray-600 hover:bg-gray-100"
                        aria-label="Pending reviews"
                    >
                        <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                        {shell.reviewPendingCount > 0 ? (
                            <span className="absolute right-0 top-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-xs text-white">
                                {shell.reviewPendingCount}
                            </span>
                        ) : null}
                    </Link>
                    <NotificationsMenu count={shell.notificationCount} />
                    <LocaleSwitcher shell={shell} />
                </div>
            </div>
        </header>
    );
}
