@extends('layouts.app')
@section('page-title', 'Keyword Research')
@section('content')
<div class="max-w-3xl space-y-4" x-data="{ keyword: '', output: '', async run() {
    try {
        const { data } = await axios.post(@json(url('/main/'.$organizationId.'/ai/seo/keyword-research')), { keyword: this.keyword });
        this.output = JSON.stringify(data.data ?? data, null, 2);
    } catch (e) { this.output = e.response?.data?.message || e.message; }
}}">
    <h1 class="text-2xl font-semibold">Keyword Research</h1>
    <div class="bg-white border rounded-lg p-6 space-y-3">
        <input x-model="keyword" class="w-full rounded-md border-gray-300" placeholder="Seed keyword">
        <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Search</button>
    </div>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection
