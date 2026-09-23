@extends('layouts.app')
@section('page-title', 'Reviews')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Reputation inbox</h1>
    <div class="grid md:grid-cols-4 gap-3">
        <div class="bg-white border rounded p-3"><p class="text-xs text-gray-500">Avg rating</p><p class="text-xl">{{ $aggregation['average_rating'] ?? $aggregation['avg'] ?? '—' }}</p></div>
        <div class="bg-white border rounded p-3 col-span-3 text-sm">Sources: {{ is_array($sources) || $sources instanceof \Illuminate\Support\Collection ? collect($sources)->pluck('name')->join(', ') : '' }}</div>
    </div>
    <form method="POST" action="{{ route('main.reviews.import', ['organizationId' => $organizationId]) }}" class="bg-white border rounded p-4 text-sm space-y-2">
        @csrf
        <input name="source_slug" placeholder="google" class="rounded-md border-gray-300">
        <textarea name="reviews_json" placeholder='[{"author":"Ada","rating":5,"content":"Great"}]' class="w-full rounded-md border-gray-300"></textarea>
        <button class="text-blue-700">Import JSON reviews</button>
    </form>
    <div class="bg-white border rounded-lg">
        @forelse($reviews as $review)
            <div class="p-4 border-b">
                <p class="font-medium">{{ $review->author }} · {{ $review->rating }}/5 · {{ $review->platform }} · {{ $review->sentiment }}</p>
                <p class="text-sm text-gray-600">{{ $review->content }}</p>
                <form method="POST" action="{{ route('main.reviews.responses.store', ['organizationId' => $organizationId, 'review' => $review]) }}" class="mt-2 flex gap-2">
                    @csrf
                    <input name="response" required class="flex-1 rounded-md border-gray-300" placeholder="Reply">
                    <input type="hidden" name="response_type" value="public">
                    <button class="text-blue-700 text-sm">Respond</button>
                </form>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No reviews.</div>
        @endforelse
    </div>
</div>
@endsection
