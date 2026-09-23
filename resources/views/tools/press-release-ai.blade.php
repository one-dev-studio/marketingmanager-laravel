@extends('layouts.app')
@section('page-title', 'Press Release')
@section('content')
<div class="max-w-3xl space-y-4" x-data="{ topic: '', output: '', async run() {
    try {
        const { data } = await axios.post(@json(url('/main/'.$organizationId.'/ai/content/press-release')), { topic: this.topic });
        this.output = JSON.stringify(data.data ?? data, null, 2);
    } catch (e) { this.output = e.response?.data?.message || e.message; }
}}">
    <h1 class="text-2xl font-semibold">AI Press Release</h1>
    <div class="bg-white border rounded-lg p-6 space-y-3">
        <input x-model="topic" class="w-full rounded-md border-gray-300" placeholder="Announcement">
        <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Generate</button>
        <a class="text-sm text-blue-700 ml-2" href="{{ route('main.press-releases.index', ['organizationId' => $organizationId]) }}">Press release library</a>
    </div>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection
