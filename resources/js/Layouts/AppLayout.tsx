import { AppHeader } from '@/components/layout/AppHeader';
import { AppSidebar } from '@/components/layout/AppSidebar';
import { CommandAssistantFab } from '@/components/layout/CommandAssistantFab';
import { FlashStatus } from '@/components/FlashStatus';
import { ClientPortalBrandingStrip } from '@/Layouts/ClientPortalLayout';
import type { AppShellContext } from '@/types/appShell';
import type { ClientPortalBranding } from '@/types/client-portal';
import type { SidebarNavigation } from '@/types/sidebar';
import { Head, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';

interface AppLayoutProps {
    title: string;
    children: ReactNode;
}

interface SharedPageProps {
    sidebar: SidebarNavigation | null;
    appShell: AppShellContext | null;
    clientPortalBranding: ClientPortalBranding | null;
    [key: string]: unknown;
}

export default function AppLayout({ title, children }: AppLayoutProps) {
    const { sidebar, appShell, clientPortalBranding } = usePage<SharedPageProps>().props;

    if (!sidebar || !appShell) {
        return (
            <>
                <Head title={title} />
                <div className="min-h-screen bg-gray-50 p-8 text-gray-700">
                    <p>Organization shell is unavailable on this page.</p>
                    {children}
                </div>
            </>
        );
    }

    const insetMargin = 'md:ml-64';

    return (
        <>
            <Head title={title} />
            <div className="min-h-screen bg-gray-50">
                <AppSidebar navigation={sidebar} />
                <div className={`flex min-h-screen min-w-0 flex-1 flex-col ${insetMargin}`}>
                    <AppHeader title={title} shell={appShell} />
                    <main className="flex-1 p-4 md:p-6">
                        <FlashStatus />
                        {clientPortalBranding?.enabled ? (
                            <ClientPortalBrandingStrip branding={clientPortalBranding} />
                        ) : null}
                        {children}
                    </main>
                </div>
                <CommandAssistantFab />
            </div>
        </>
    );
}
