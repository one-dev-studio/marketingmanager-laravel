import { Button } from '@/components/ui/button';
import { paginationLabelText } from '@/lib/paginationLabel';
import AppLayout from '@/Layouts/AppLayout';
import type { ContactListItem, ContactListOption } from '@/types/contacts';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedContacts {
    data: ContactListItem[];
    links: PaginationLink[];
}

export interface ContactsIndexProps {
    organizationId: string;
    contacts: PaginatedContacts;
    lists: ContactListOption[];
    filters: {
        search?: string;
        status?: string;
        contact_list_id?: string;
    };
}

export default function Index({ organizationId, contacts, lists, filters }: ContactsIndexProps) {
    const items = contacts.data ?? [];

    const deleteContact = (contactId: number) => {
        if (!window.confirm('Delete this contact?')) {
            return;
        }
        router.delete(
            route('main.email-marketing.contacts.destroy', {
                organizationId,
                contact: contactId,
            }),
        );
    };

    return (
        <AppLayout title="Contacts">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Contacts</h1>
                        <p className="mt-1 text-sm text-gray-600">Email marketing audience</p>
                    </div>
                    <Link href={route('main.email-marketing.contacts.create', { organizationId })}>
                        <Button type="button">Add contact</Button>
                    </Link>
                </div>

                <form
                    method="get"
                    action={route('main.email-marketing.contacts.index', { organizationId })}
                    className="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-white p-4"
                >
                    <div>
                        <label className="text-xs font-medium text-gray-500" htmlFor="search">
                            Search
                        </label>
                        <input
                            id="search"
                            name="search"
                            value={filters.search ?? ''}
                            className="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                        />
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500" htmlFor="status">
                            Status
                        </label>
                        <select
                            id="status"
                            name="status"
                            value={filters.status ?? ''}
                            className="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                        >
                            <option value="">All</option>
                            <option value="active">active</option>
                            <option value="unsubscribed">unsubscribed</option>
                            <option value="bounced">bounced</option>
                            <option value="invalid">invalid</option>
                        </select>
                    </div>
                    <div>
                        <label className="text-xs font-medium text-gray-500" htmlFor="contact_list_id">
                            List
                        </label>
                        <select
                            id="contact_list_id"
                            name="contact_list_id"
                            value={filters.contact_list_id ?? ''}
                            className="mt-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                        >
                            <option value="">All</option>
                            {lists.map((list) => (
                                <option key={list.id} value={list.id}>
                                    {list.name}
                                </option>
                            ))}
                        </select>
                    </div>
                    <Button type="submit">Filter</Button>
                </form>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No contacts yet.</div>
                    ) : (
                        items.map((contact) => (
                            <div
                                key={contact.id}
                                className="flex items-start justify-between gap-4 border-b border-gray-100 p-4 last:border-b-0 hover:bg-gray-50"
                            >
                                <div className="min-w-0">
                                    <Link
                                        href={route('main.email-marketing.contacts.show', {
                                            organizationId,
                                            contact: contact.id,
                                        })}
                                        className="font-medium text-gray-900 hover:text-primary-700"
                                    >
                                        {contact.full_name || contact.email}
                                    </Link>
                                    <p className="mt-1 text-sm text-gray-500">
                                        {contact.email} · {contact.status}
                                        {contact.company ? ` · ${contact.company}` : ''}
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    onClick={() => deleteContact(contact.id)}
                                    className="shrink-0 text-sm text-red-600 hover:text-red-800"
                                >
                                    Delete
                                </button>
                            </div>
                        ))
                    )}
                </div>

                {contacts.links?.length > 1 ? (
                    <div className="flex flex-wrap gap-2">
                        {contacts.links.map((link, index) =>
                            link.url ? (
                                <Link
                                    key={index}
                                    href={link.url}
                                    className={`rounded px-3 py-1 text-sm ${
                                        link.active
                                            ? 'bg-primary-600 text-white'
                                            : 'border border-gray-200 text-gray-700 hover:bg-gray-50'
                                    }`}
                                    >{paginationLabelText(link.label)}</Link>
                            ) : null,
                        )}
                    </div>
                ) : null}
            </div>
        </AppLayout>
    );
}
