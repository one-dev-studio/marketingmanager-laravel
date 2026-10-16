import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface ContestEntry {
    id: number;
    entrant_name: string;
    entrant_email: string;
    answer: string | null;
    is_winner: boolean;
}

interface ContestDetail {
    id: number;
    name: string;
    status: string;
    prize: string | null;
    entries: ContestEntry[];
}

export interface CompetitionsShowProps {
    organizationId: string;
    contest: ContestDetail;
}

export default function Show({ organizationId, contest }: CompetitionsShowProps) {
    const entryForm = useForm({
        entrant_name: '',
        entrant_email: '',
        answer: '',
    });

    const submitEntry = (event: FormEvent) => {
        event.preventDefault();
        entryForm.post(
            route('main.competitions.entries.store', {
                organizationId,
                contest: contest.id,
            }),
            { preserveScroll: true, onSuccess: () => entryForm.reset() },
        );
    };

    const closeContest = () => {
        entryForm.post(
            route('main.competitions.close', {
                organizationId,
                contest: contest.id,
            }),
        );
    };

    return (
        <AppLayout title={contest.name}>
            <div className="space-y-4">
                <div className="flex items-start justify-between gap-4">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{contest.name}</h1>
                        <p className="text-sm text-gray-500">
                            {contest.status} · prize {contest.prize ?? '—'}
                        </p>
                    </div>
                    <Button type="button" variant="outline" onClick={closeContest}>
                        Close & pick winner
                    </Button>
                </div>

                <form
                    onSubmit={submitEntry}
                    className="grid gap-2 rounded-lg border border-gray-200 bg-white p-4 md:grid-cols-4"
                >
                    <input
                        name="entrant_name"
                        required
                        placeholder="Name"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={entryForm.data.entrant_name}
                        onChange={(e) => entryForm.setData('entrant_name', e.target.value)}
                    />
                    <input
                        name="entrant_email"
                        type="email"
                        required
                        placeholder="Email"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={entryForm.data.entrant_email}
                        onChange={(e) => entryForm.setData('entrant_email', e.target.value)}
                    />
                    <input
                        name="answer"
                        placeholder="Entry"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={entryForm.data.answer}
                        onChange={(e) => entryForm.setData('answer', e.target.value)}
                    />
                    <Button type="submit" disabled={entryForm.processing}>Add entry</Button>
                </form>

                <div className="rounded-lg border border-gray-200 bg-white">
                    {contest.entries.map((entry) => (
                        <div
                            key={entry.id}
                            className="flex items-center justify-between border-b border-gray-100 p-3 text-sm last:border-b-0"
                        >
                            <span>
                                {entry.entrant_name} · {entry.entrant_email}
                                {entry.answer ? ` · ${entry.answer}` : ''}
                            </span>
                            {entry.is_winner && <span className="text-green-700">Winner</span>}
                        </div>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
