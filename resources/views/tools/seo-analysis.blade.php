@extends('layouts.app')
@section('page-title', 'SEO Analysis')
@section('content')
<div class="max-w-3xl space-y-4" x-data="{ url: '', output: '', loading: false, async run() {
    this.loading = true;
    try {
        const { data } = await axios.post(@json(url('/main/'.$organizationId.'/ai/seo/analyze-content')), { url: this.url, content: 'Page content to analyze' });
        this.output = JSON.stringify(data.data ?? data, null, 2);
    } catch (e) { this.output = e.response?.data?.message || e.message; }
    finally { this.loading = false; }
}}">
    <h1 class="text-2xl font-semibold">SEO Analysis</h1>
    <div class="bg-white border rounded-lg p-6 space-y-3">
        <input x-model="url" placeholder="https://example.com/page" class="w-full rounded-md border-gray-300">
        <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="loading">Analyze</button>
    </div>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection
