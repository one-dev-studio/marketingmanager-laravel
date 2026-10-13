import MinimalAuthenticatedLayout from '@/Layouts/MinimalAuthenticatedLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface OrganizationListItem {
    id: number;
    name: string;
    status: string | null;
}

export interface OrganizationsIndexProps {
    organizations: OrganizationListItem[];
}

export default function Index({ organizations }: OrganizationsIndexProps) {
    return (
        <MinimalAuthenticatedLayout title="Organizations">
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Your organizations</h1>
                    <p className="mt-1 text-sm text-gray-600">
                        Select an organization to continue, or complete onboarding to create one.
                    </p>
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {organizations.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">
                            <p>You don&apos;t belong to an organization yet.</p>
                            <Link
                                href={route('main.onboarding')}
                                className="mt-4 inline-block rounded-md bg-primary-600 px-4 py-2 text-white hover:bg-primary-700"
                            >
                                Start onboarding
                            </Link>
                        </div>
                    ) : (
                        organizations.map((organization) => (
                            <Link
                                key={organization.id}
                                href={route('main.dashboard', { organizationId: organization.id })}
                                className="block border-b border-gray-100 p-4 last:border-b-0 hover:bg-gray-50"
                            >
                                <p className="font-medium text-gray-900">{organization.name}</p>
                                <p className="text-sm text-gray-500">{organization.status ?? '—'}</p>
                            </Link>
                        ))
                    )}
                </div>

                {organizations.length > 0 ? (
                    <Link
                        href={route('main.onboarding')}
                        className="text-sm text-primary-600 hover:text-primary-800"
                    >
                        Run setup wizard
                    </Link>
                ) : null}
            </div>
        </MinimalAuthenticatedLayout>
    );
}
