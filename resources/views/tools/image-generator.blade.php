@extends('layouts.app')
@section('page-title', 'Image Generator')
@section('content')
<div class="max-w-3xl space-y-4" x-data="{ prompt: '', style: 'realistic', size: '1024x1024', output: '', loading: false, async run() {
    this.loading = true;
    try {
        const { data } = await axios.post(@json(url('/main/'.$organizationId.'/ai/images/generate')), { prompt: this.prompt, style: this.style, size: this.size });
        this.output = JSON.stringify(data.data ?? data, null, 2);
    } catch (e) { this.output = e.response?.data?.message || e.message; }
    finally { this.loading = false; }
}}">
    <h1 class="text-2xl font-semibold">Image Generator</h1>
    <div class="bg-white border rounded-lg p-6 space-y-3">
        <textarea x-model="prompt" rows="3" class="w-full rounded-md border-gray-300" placeholder="Prompt"></textarea>
        <div class="grid grid-cols-2 gap-3">
            <select x-model="style" class="rounded-md border-gray-300"><option>realistic</option><option>artistic</option><option>minimalist</option><option>vintage</option><option>modern</option></select>
            <select x-model="size" class="rounded-md border-gray-300"><option>1024x1024</option><option>1792x1024</option><option>1024x1792</option></select>
        </div>
        <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Generate</button>
    </div>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection
