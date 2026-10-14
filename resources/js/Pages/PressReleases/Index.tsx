import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { router, useForm } from '@inertiajs/react';
import axios from 'axios';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface PressReleaseListItem {
    id: number;
    title: string;
    status: string;
    release_date: string | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedPressReleases {
    data: PressReleaseListItem[];
    links: PaginationLink[];
}

export interface PressReleasesIndexProps {
    organizationId: string;
    pressReleases: PaginatedPressReleases;
}

export default function Index({ organizationId, pressReleases }: PressReleasesIndexProps) {
    const createForm = useForm({
        title: '',
        content: '',
        release_date: '',
    });
    const contactForm = useForm({
        name: '',
        email: '',
        media_outlet: '',
    });

    const items = pressReleases.data ?? [];

    const submitCreate = (event: FormEvent) => {
        event.preventDefault();
        createForm.post(route('main.press-releases.store', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => createForm.reset(),
        });
    };

    const submitContact = (event: FormEvent) => {
        event.preventDefault();
        contactForm.post(route('main.press-releases.contacts.store', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => contactForm.reset(),
        });
    };

    const scheduleRelease = async (pressReleaseId: number) => {
        const releaseDate = window.prompt('Release date (ISO datetime)', new Date(Date.now() + 86400000).toISOString());
        if (!releaseDate) {
            return;
        }
        await axios.post(
            route('main.press-releases.schedule', { organizationId, pressRelease: pressReleaseId }),
            { release_date: releaseDate },
        );
        router.reload({ only: ['pressReleases'] });
    };

    const approveRelease = async (pressReleaseId: number) => {
        await axios.post(route('main.press-releases.approve', { organizationId, pressRelease: pressReleaseId }));
        router.reload({ only: ['pressReleases'] });
    };

    return (
        <AppLayout title="Press Releases">
            <div className="space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">Press releases</h1>

                <form onSubmit={submitCreate} className="space-y-2 rounded-lg border border-gray-200 bg-white p-4">
                    <input
                        name="title"
                        required
                        placeholder="Title"
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={createForm.data.title}
                        onChange={(e) => createForm.setData('title', e.target.value)}
                    />
                    <textarea
                        name="content"
                        required
                        rows={4}
                        className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                        placeholder="Body"
                        value={createForm.data.content}
                        onChange={(e) => createForm.setData('content', e.target.value)}
                    />
                    <input
                        type="datetime-local"
                        name="release_date"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={createForm.data.release_date}
                        onChange={(e) => createForm.setData('release_date', e.target.value)}
                    />
                    <Button type="submit" disabled={createForm.processing}>Create</Button>
                </form>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">None yet.</div>
                    ) : (
                        items.map((release) => (
                            <div key={release.id} className="border-b border-gray-100 p-4 last:border-b-0">
                                <p className="font-medium text-gray-900">{release.title}</p>
                                <p className="text-sm text-gray-500">
                                    {release.status} · {release.release_date ?? '—'}
                                </p>
                                <div className="mt-1 flex gap-2 text-sm">
                                    <button
                                        type="button"
                                        className="text-primary-700 hover:underline"
                                        onClick={() => scheduleRelease(release.id)}
                                    >
                                        Schedule
                                    </button>
                                    <button
                                        type="button"
                                        className="text-gray-700 hover:underline"
                                        onClick={() => approveRelease(release.id)}
                                    >
                                        Approve
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-4">
                    <h2 className="mb-2 font-medium text-gray-900">Media contacts</h2>
                    <form onSubmit={submitContact} className="grid gap-2 md:grid-cols-4">
                        <input
                            name="name"
                            required
                            placeholder="Name"
                            className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={contactForm.data.name}
                            onChange={(e) => contactForm.setData('name', e.target.value)}
                        />
                        <input
                            name="email"
                            type="email"
                            required
                            placeholder="Email"
                            className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={contactForm.data.email}
                            onChange={(e) => contactForm.setData('email', e.target.value)}
                        />
                        <input
                            name="media_outlet"
                            placeholder="Outlet"
                            className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={contactForm.data.media_outlet}
                            onChange={(e) => contactForm.setData('media_outlet', e.target.value)}
                        />
                        <Button type="submit" variant="outline" className="justify-start md:justify-center" disabled={contactForm.processing}>
                            Add
                        </Button>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
