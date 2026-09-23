@extends('layouts.app')
@section('page-title', 'Analytics')
@section('content')
<div class="grid lg:grid-cols-3 gap-6" x-data="{ loading: false, result: null, async analyze() {
    this.loading = true;
    const form = new FormData($refs.form);
    try {
        const { data } = await axios.post($refs.form.action, Object.fromEntries(form));
        this.result = data.data || data;
    } catch (e) { this.result = { error: e.response?.data?.message || e.message }; }
    finally { this.loading = false; }
}}">
    <form x-ref="form" class="bg-white border rounded-lg p-4 space-y-3" action="{{ route('main.analytics.analyze', ['organizationId' => $organizationId]) }}?brandId={{ request('brandId') }}" @submit.prevent="analyze">
        <h1 class="text-xl font-semibold">Analyze</h1>
        <p class="text-sm text-gray-500">{{ $brand->name ?? '' }}</p>
        <select name="campaign_id" class="w-full rounded-md border-gray-300">
            <option value="">Campaign / scheduled posts</option>
            @foreach($campaigns as $campaign)
                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
            @endforeach
        </select>
        <input name="client_name" placeholder="Optional client name" class="w-full rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md w-full">Analyze</button>
        @if($reports->count())
            <p class="text-xs text-gray-400">Saved reports: {{ $reports->count() }}</p>
        @endif
    </form>
    <div class="lg:col-span-2 bg-white border rounded-lg p-4 min-h-[20rem]">
        <template x-if="!result"><p class="text-gray-500">Results appear here.</p></template>
        <pre class="text-sm whitespace-pre-wrap" x-text="JSON.stringify(result, null, 2)"></pre>
    </div>
</div>
@endsection
