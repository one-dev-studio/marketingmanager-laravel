import { AppSidebar } from '@/components/layout/AppSidebar';
import { FlashStatus } from '@/components/FlashStatus';
import type { SidebarNavigation } from '@/types/sidebar';
import { Head, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';

interface AppLayoutProps {
    title: string;
    children: ReactNode;
}

interface SharedPageProps {
    sidebar: SidebarNavigation | null;
    [key: string]: unknown;
}

export default function AppLayout({ title, children }: AppLayoutProps) {
    const { sidebar } = usePage<SharedPageProps>().props;

    if (!sidebar) {
        return (
            <>
                <Head title={title} />
                <div className="min-h-screen bg-gray-50 p-8 text-gray-700">
                    <p>Organization navigation is unavailable on this page.</p>
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
                    <header className="sticky top-0 z-20 border-b border-gray-200 bg-white px-4 py-4 md:px-6">
                        <h1 className="text-xl font-semibold text-gray-900">{title}</h1>
                    </header>
                    <main className="flex-1 p-4 md:p-6">
                        <FlashStatus />
                        {children}
                    </main>
                </div>
            </div>
        </>
    );
}
