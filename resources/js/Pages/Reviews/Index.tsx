import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { router, useForm } from '@inertiajs/react';
import axios from 'axios';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface ReviewListItem {
    id: number;
    author: string | null;
    rating: number;
    platform: string | null;
    sentiment: string | null;
    content: string;
}

interface ReviewSource {
    id: number;
    name: string;
    slug: string;
}

interface ReviewAggregation {
    average_rating?: number;
    total_reviews?: number;
}

export interface ReviewsIndexProps {
    organizationId: string;
    reviews: ReviewListItem[];
    aggregation: ReviewAggregation;
    sources: ReviewSource[];
}

export default function Index({ organizationId, reviews, aggregation, sources }: ReviewsIndexProps) {
    const importForm = useForm({ source_slug: '', reviews_json: '' });
    const sourceNames = sources.map((source) => source.name).join(', ');

    const submitImport = (event: FormEvent) => {
        event.preventDefault();
        importForm.post(route('main.reviews.import', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => importForm.reset(),
        });
    };

    const submitResponse = async (reviewId: number, response: string) => {
        await axios.post(
            route('main.reviews.responses.store', { organizationId, review: reviewId }),
            { response, response_type: 'public' },
        );
        router.reload({ only: ['reviews', 'aggregation'] });
    };

    return (
        <AppLayout title="Reviews">
            <div className="space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">Reputation inbox</h1>
                <div className="grid gap-3 md:grid-cols-4">
                    <div className="rounded border border-gray-200 bg-white p-3">
                        <p className="text-xs text-gray-500">Avg rating</p>
                        <p className="text-xl text-gray-900">{aggregation.average_rating ?? '—'}</p>
                    </div>
                    <div className="col-span-3 rounded border border-gray-200 bg-white p-3 text-sm text-gray-700">
                        Sources: {sourceNames || '—'}
                    </div>
                </div>

                <form
                    onSubmit={submitImport}
                    className="space-y-2 rounded border border-gray-200 bg-white p-4 text-sm"
                >
                    <input
                        name="source_slug"
                        placeholder="google"
                        className="rounded-md border border-gray-300 px-3 py-2"
                        value={importForm.data.source_slug}
                        onChange={(e) => importForm.setData('source_slug', e.target.value)}
                    />
                    <textarea
                        name="reviews_json"
                        placeholder='[{"author":"Ada","rating":5,"content":"Great"}]'
                        className="w-full rounded-md border border-gray-300 px-3 py-2"
                        value={importForm.data.reviews_json}
                        onChange={(e) => importForm.setData('reviews_json', e.target.value)}
                    />
                    <Button type="submit" variant="outline" className="h-auto" disabled={importForm.processing}>
                        Import JSON reviews
                    </Button>
                </form>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {reviews.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No reviews.</div>
                    ) : (
                        reviews.map((review) => (
                            <ReviewRow
                                key={review.id}
                                review={review}
                                onRespond={(text) => submitResponse(review.id, text)}
                            />
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}

function ReviewRow({
    review,
    onRespond,
}: {
    review: ReviewListItem;
    onRespond: (text: string) => void;
}) {
    const responseForm = useForm({ response: '' });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        if (!responseForm.data.response.trim()) {
            return;
        }
        onRespond(responseForm.data.response);
        responseForm.reset();
    };

    return (
        <div className="border-b border-gray-100 p-4 last:border-b-0">
            <p className="font-medium text-gray-900">
                {review.author ?? 'Anonymous'} · {review.rating}/5 · {review.platform ?? '—'} ·{' '}
                {review.sentiment ?? '—'}
            </p>
            <p className="text-sm text-gray-600">{review.content}</p>
            <form onSubmit={submit} className="mt-2 flex gap-2">
                <input
                    required
                    className="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                    placeholder="Reply"
                    value={responseForm.data.response}
                    onChange={(e) => responseForm.setData('response', e.target.value)}
                />
                <Button type="submit" variant="outline" className="shrink-0 text-sm">
                    Respond
                </Button>
            </form>
        </div>
    );
}
