import { FlashStatus } from '@/components/FlashStatus';
import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';

interface MinimalAuthenticatedLayoutProps {
    title: string;
    children: ReactNode;
}

export default function MinimalAuthenticatedLayout({
    title,
    children,
}: MinimalAuthenticatedLayoutProps) {
    return (
        <>
            <Head title={title} />
            <div className="min-h-screen bg-gray-50">
                <header className="border-b border-gray-200 bg-white">
                    <div className="mx-auto flex max-w-3xl items-center justify-between px-4 py-4">
                        <Link href={route('home')} className="text-lg font-bold text-gray-900">
                            MarketPulse
                        </Link>
                        <Link
                            href={route('main.organizations')}
                            className="text-sm text-primary-600 hover:text-primary-700"
                        >
                            Organizations
                        </Link>
                    </div>
                </header>
                <main className="mx-auto max-w-3xl px-4 py-8">
                    <FlashStatus />
                    {children}
                </main>
            </div>
        </>
    );
}
