import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { ContactDetail } from '@/types/contacts';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ContactsShowProps {
    organizationId: string;
    contact: ContactDetail;
    duplicateEmails: string[];
}

export default function Show({ organizationId, contact, duplicateEmails }: ContactsShowProps) {
    const subscribe = () => {
        router.post(
            route('main.email-marketing.contacts.subscribe', {
                organizationId,
                contact: contact.id,
            }),
        );
    };

    const unsubscribe = () => {
        router.post(
            route('main.email-marketing.contacts.unsubscribe', {
                organizationId,
                contact: contact.id,
            }),
        );
    };

    const deleteContact = () => {
        if (!window.confirm('Delete this contact?')) {
            return;
        }
        router.delete(
            route('main.email-marketing.contacts.destroy', {
                organizationId,
                contact: contact.id,
            }),
        );
    };

    return (
        <AppLayout title={contact.full_name || contact.email}>
            <div className="mx-auto max-w-2xl space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">
                            {contact.full_name || contact.email}
                        </h1>
                        <p className="mt-1 text-sm text-gray-600">
                            {contact.email} · {contact.status}
                        </p>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        <Button type="button" variant="outline" onClick={subscribe}>
                            Subscribe
                        </Button>
                        <Button type="button" variant="outline" onClick={unsubscribe}>
                            Unsubscribe
                        </Button>
                        <button
                            type="button"
                            onClick={deleteContact}
                            className="text-sm text-red-600 hover:text-red-800"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                {duplicateEmails.length > 0 ? (
                    <div className="rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-900">
                        Possible duplicates: {duplicateEmails.join(', ')}
                    </div>
                ) : null}

                <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Phone</h2>
                        <p className="mt-1 text-gray-900">{contact.phone || '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Company</h2>
                        <p className="mt-1 text-gray-900">{contact.company || '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Job title</h2>
                        <p className="mt-1 text-gray-900">{contact.job_title || '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Source</h2>
                        <p className="mt-1 text-gray-900">{contact.source || '—'}</p>
                    </div>
                    <div>
                        <h2 className="text-sm font-medium text-gray-500">Tags</h2>
                        <p className="mt-1 text-gray-900">
                            {contact.tags.length > 0 ? contact.tags.join(', ') : '—'}
                        </p>
                    </div>
                </div>

                <Link
                    href={route('main.email-marketing.contacts.index', { organizationId })}
                    className="text-sm text-primary-600 hover:text-primary-800"
                >
                    Back to contacts
                </Link>
            </div>
        </AppLayout>
    );
}
