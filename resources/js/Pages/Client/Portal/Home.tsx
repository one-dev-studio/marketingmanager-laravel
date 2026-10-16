import ClientPortalLayout from '@/Layouts/ClientPortalLayout';
import type { ClientPortalBranding, ClientPortalOrganizationLink } from '@/types/client-portal';
import { Link } from '@inertiajs/react';

export interface ClientPortalHomeProps {
    agency: { id: number; name: string };
    portalBranding: ClientPortalBranding;
    organizations: ClientPortalOrganizationLink[];
}

export default function Home({ portalBranding, organizations }: ClientPortalHomeProps) {
    return (
        <ClientPortalLayout title={portalBranding.title} portalBranding={portalBranding}>
            <div className="space-y-6 rounded-lg border border-gray-200 bg-white p-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Welcome</h1>
                    <p className="mt-2 text-sm text-gray-600">
                        {portalBranding.welcome_text ??
                            'Choose a workspace to view your campaigns and reports.'}
                    </p>
                </div>

                {organizations.length === 0 ? (
                    <p className="text-sm text-gray-600">No workspaces are linked to your account.</p>
                ) : (
                    <ul className="divide-y divide-gray-100 rounded-md border border-gray-200">
                        {organizations.map((organization) => (
                            <li key={organization.id} className="flex items-center justify-between p-4">
                                <span className="font-medium text-gray-900">{organization.name}</span>
                                <Link
                                    href={organization.workspace_href ?? organization.dashboard_href}
                                    className="text-sm font-medium"
                                    style={{ color: 'var(--client-portal-primary)' }}
                                >
                                    Open workspace
                                </Link>
                            </li>
                        ))}
                    </ul>
                )}
            </div>
        </ClientPortalLayout>
    );
}
