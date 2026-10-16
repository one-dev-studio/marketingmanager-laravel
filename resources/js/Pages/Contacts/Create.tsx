import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import type { ContactListOption } from '@/types/contacts';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface ContactFormData {
    email: string;
    first_name: string;
    last_name: string;
    phone: string;
    company: string;
    job_title: string;
    contact_list_ids: number[];
}

export interface ContactsCreateProps {
    organizationId: string;
    lists: ContactListOption[];
}

export default function Create({ organizationId, lists }: ContactsCreateProps) {
    const form = useForm<ContactFormData>({
        email: '',
        first_name: '',
        last_name: '',
        phone: '',
        company: '',
        job_title: '',
        contact_list_ids: [],
    });

    const toggleList = (listId: number) => {
        const ids = form.data.contact_list_ids;
        if (ids.includes(listId)) {
            form.setData('contact_list_ids', ids.filter((id) => id !== listId));
        } else {
            form.setData('contact_list_ids', [...ids, listId]);
        }
    };

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.email-marketing.contacts.store', { organizationId }));
    };

    return (
        <AppLayout title="Add Contact">
            <div className="mx-auto max-w-2xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Add contact</h1>
                </div>
                <form onSubmit={submit} className="space-y-4">
                    <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                        <div>
                            <Label htmlFor="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                value={form.data.email}
                                onChange={(e) => form.setData('email', e.target.value)}
                                required
                            />
                            <InputError message={form.errors.email} />
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            <div>
                                <Label htmlFor="first_name">First name</Label>
                                <Input
                                    id="first_name"
                                    value={form.data.first_name}
                                    onChange={(e) => form.setData('first_name', e.target.value)}
                                />
                                <InputError message={form.errors.first_name} />
                            </div>
                            <div>
                                <Label htmlFor="last_name">Last name</Label>
                                <Input
                                    id="last_name"
                                    value={form.data.last_name}
                                    onChange={(e) => form.setData('last_name', e.target.value)}
                                />
                                <InputError message={form.errors.last_name} />
                            </div>
                        </div>
                        <div>
                            <Label htmlFor="phone">Phone</Label>
                            <Input
                                id="phone"
                                value={form.data.phone}
                                onChange={(e) => form.setData('phone', e.target.value)}
                            />
                            <InputError message={form.errors.phone} />
                        </div>
                        <div>
                            <Label htmlFor="company">Company</Label>
                            <Input
                                id="company"
                                value={form.data.company}
                                onChange={(e) => form.setData('company', e.target.value)}
                            />
                            <InputError message={form.errors.company} />
                        </div>
                        <div>
                            <Label htmlFor="job_title">Job title</Label>
                            <Input
                                id="job_title"
                                value={form.data.job_title}
                                onChange={(e) => form.setData('job_title', e.target.value)}
                            />
                            <InputError message={form.errors.job_title} />
                        </div>
                        {lists.length > 0 ? (
                            <div>
                                <p className="text-sm font-medium text-gray-700">Lists</p>
                                <ul className="mt-2 space-y-2">
                                    {lists.map((list) => (
                                        <li key={list.id}>
                                            <label className="flex items-center gap-2 text-sm">
                                                <input
                                                    type="checkbox"
                                                    checked={form.data.contact_list_ids.includes(list.id)}
                                                    onChange={() => toggleList(list.id)}
                                                />
                                                {list.name}
                                            </label>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ) : null}
                    </div>
                    <div className="flex items-center gap-3">
                        <Button type="submit" disabled={form.processing}>Save contact</Button>
                        <Link
                            href={route('main.email-marketing.contacts.index', { organizationId })}
                            className="text-sm text-gray-600 hover:text-gray-900"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
