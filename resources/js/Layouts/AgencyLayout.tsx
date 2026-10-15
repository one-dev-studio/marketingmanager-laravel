import { AgencyHeader } from '@/components/layout/AgencyHeader';
import { AgencySidebar } from '@/components/layout/AgencySidebar';
import { FlashStatus } from '@/components/FlashStatus';
import type { AgencyShellContext, AgencySidebarNavigation } from '@/types/agencyShell';
import { Head, usePage } from '@inertiajs/react';
import { useState, type ReactNode } from 'react';

interface AgencyLayoutProps {
    title: string;
    children: ReactNode;
}

interface SharedPageProps {
    agencySidebar: AgencySidebarNavigation | null;
    agencyShell: AgencyShellContext | null;
    [key: string]: unknown;
}

export default function AgencyLayout({ title, children }: AgencyLayoutProps) {
    const { agencySidebar, agencyShell } = usePage<SharedPageProps>().props;
    const [mobileNavOpen, setMobileNavOpen] = useState(false);

    if (!agencySidebar || !agencyShell) {
        return (
            <>
                <Head title={title} />
                <div className="min-h-screen bg-gray-50 p-8 text-gray-700">
                    <p>Agency shell is unavailable on this page.</p>
                    {children}
                </div>
            </>
        );
    }

    return (
        <>
            <Head title={title} />
            <div className="min-h-screen bg-gray-50">
                <AgencySidebar
                    navigation={agencySidebar}
                    exitAgencyHref={agencyShell.exitAgencyHref}
                    mobileOpen={mobileNavOpen}
                    onMobileClose={() => setMobileNavOpen(false)}
                />
                <div className="flex min-h-screen min-w-0 flex-1 flex-col md:ml-64">
                    <AgencyHeader
                        title={title}
                        shell={agencyShell}
                        onMobileMenuToggle={() => setMobileNavOpen((open) => !open)}
                    />
                    <main className="flex-1 p-4 md:p-6">
                        <FlashStatus />
                        {children}
                    </main>
                </div>
            </div>
        </>
    );
}
