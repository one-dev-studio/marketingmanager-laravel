@extends('layouts.app')
@section('page-title', 'Email Template')
@section('content')
<div class="max-w-3xl space-y-4" x-data="{ purpose: '', audience: '', output: '', loading: false, async run() {
    this.loading = true;
    try {
        const { data } = await axios.post(@json(url('/main/'.$organizationId.'/ai/content/email')), { purpose: this.purpose, audience: this.audience });
        this.output = JSON.stringify(data.data ?? data, null, 2);
    } catch (e) { this.output = e.response?.data?.message || e.message; }
    finally { this.loading = false; }
}}">
    <h1 class="text-2xl font-semibold">AI Email Template</h1>
    <div class="bg-white border rounded-lg p-6 space-y-3">
        <textarea x-model="purpose" rows="3" placeholder="Purpose" class="w-full rounded-md border-gray-300"></textarea>
        <input x-model="audience" placeholder="Audience" class="w-full rounded-md border-gray-300">
        <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Generate</button>
        <a class="text-sm text-blue-700 ml-2" href="{{ route('main.email-marketing.templates.builder', ['organizationId' => $organizationId]) }}">Open builder</a>
    </div>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection
