import { AdminHeader } from '@/components/layout/AdminHeader';
import { AdminSidebar } from '@/components/layout/AdminSidebar';
import { FlashStatus } from '@/components/FlashStatus';
import type { AdminShellContext, AdminSidebarNavigation } from '@/types/adminShell';
import { Head, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';

interface AdminLayoutProps {
    title: string;
    children: ReactNode;
}

interface SharedPageProps {
    adminSidebar: AdminSidebarNavigation | null;
    adminShell: AdminShellContext | null;
    [key: string]: unknown;
}

export default function AdminLayout({ title, children }: AdminLayoutProps) {
    const { adminSidebar, adminShell } = usePage<SharedPageProps>().props;

    if (!adminSidebar || !adminShell) {
        return (
            <>
                <Head title={title} />
                <div className="min-h-screen bg-gray-100 p-8 text-gray-700">
                    <p>Admin shell is unavailable on this page.</p>
                    {children}
                </div>
            </>
        );
    }

    return (
        <>
            <Head title={title} />
            <div className="min-h-screen bg-gray-100">
                <AdminSidebar
                    navigation={adminSidebar}
                    returnToAppHref={adminShell.returnToAppHref}
                    userName={adminShell.userName}
                />
                <div className="flex min-h-screen min-w-0 flex-1 flex-col md:ml-64">
                    <AdminHeader title={title} shell={adminShell} />
                    <main className="flex-1 p-4 md:p-6">
                        <FlashStatus />
                        <div className="rounded-lg bg-white p-6 shadow">{children}</div>
                    </main>
                </div>
            </div>
        </>
    );
}
