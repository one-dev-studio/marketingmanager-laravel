import { FlashStatus } from '@/components/FlashStatus';
import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import type { ReactNode } from 'react';

interface GuestLayoutProps {
    title: string;
    subtitle?: string;
    children: ReactNode;
}

export default function GuestLayout({ title, subtitle, children }: GuestLayoutProps) {
    return (
        <>
            <Head title={title} />
            <div className="flex min-h-screen items-center justify-center bg-gray-50 px-4">
                <div className="w-full max-w-md">
                    <div className="mb-8 text-center">
                        <Link href={route('home')} className="text-2xl font-bold text-gray-900">
                            MarketPulse
                        </Link>
                        {subtitle ? <p className="mt-2 text-sm text-gray-600">{subtitle}</p> : null}
                    </div>

                    <div className="rounded-lg bg-white p-8 shadow">
                        <FlashStatus />
                        {children}
                    </div>
                </div>
            </div>
        </>
    );
}
