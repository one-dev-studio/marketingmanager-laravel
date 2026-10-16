import { FlashStatus } from '@/components/FlashStatus';
import type { ClientPortalBranding } from '@/types/client-portal';
import { Head, Link } from '@inertiajs/react';
import type { CSSProperties, ReactNode } from 'react';

interface ClientPortalLayoutProps {
    title: string;
    portalBranding: ClientPortalBranding;
    children: ReactNode;
}

export default function ClientPortalLayout({
    title,
    portalBranding,
    children,
}: ClientPortalLayoutProps) {
    const style = portalBranding.css_variables as CSSProperties;

    return (
        <>
            <Head title={title} />
            <div className="min-h-screen bg-gray-50" style={style}>
                <header
                    className="border-b border-gray-200 bg-white"
                    style={{ borderBottomColor: 'var(--client-portal-primary)' }}
                >
                    <div className="mx-auto flex max-w-4xl items-center gap-4 px-4 py-4 md:px-6">
                        {portalBranding.logo_url ? (
                            <img
                                src={portalBranding.logo_url}
                                alt=""
                                className="h-10 w-auto max-w-[160px] object-contain"
                            />
                        ) : null}
                        <div>
                            <p className="text-lg font-semibold text-gray-900">
                                {portalBranding.title}
                            </p>
                            {portalBranding.welcome_text ? (
                                <p className="text-sm text-gray-600">
                                    {portalBranding.welcome_text}
                                </p>
                            ) : null}
                        </div>
                    </div>
                </header>
                <main className="mx-auto max-w-4xl px-4 py-8 md:px-6">
                    <FlashStatus />
                    {children}
                </main>
            </div>
        </>
    );
}

interface ClientPortalBrandingStripProps {
    branding: ClientPortalBranding;
}

/** Compact branded strip for organization app shell when portal white-label is active. */
export function ClientPortalBrandingStrip({ branding }: ClientPortalBrandingStripProps) {
    const href = branding.portal_home_href;
    const style = branding.css_variables as CSSProperties;

    return (
        <div
            className="mb-4 rounded-md border px-4 py-2 text-sm text-white"
            style={{ ...style, backgroundColor: 'var(--client-portal-primary)' }}
        >
            {href ? (
                <Link href={href} className="font-medium underline-offset-2 hover:underline">
                    {branding.title} client portal
                </Link>
            ) : (
                <span className="font-medium">{branding.title}</span>
            )}
        </div>
    );
}
