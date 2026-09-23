@extends('layouts.app')
@section('page-title', 'Ad Copy')
@section('content')
<div class="max-w-3xl space-y-4" x-data="{ platform: 'facebook', product: '', objective: '', output: '', async run() {
    try {
        const { data } = await axios.post(@json(url('/main/'.$organizationId.'/ai/content/ad-copy')), { platform: this.platform, product: this.product, objective: this.objective });
        this.output = JSON.stringify(data.data ?? data, null, 2);
    } catch (e) { this.output = e.response?.data?.message || e.message; }
}}">
    <h1 class="text-2xl font-semibold">Ad Copy</h1>
    <div class="bg-white border rounded-lg p-6 space-y-3">
        <select x-model="platform" class="w-full rounded-md border-gray-300">
            <option>facebook</option><option>google</option><option>linkedin</option><option>tiktok</option>
        </select>
        <input x-model="product" class="w-full rounded-md border-gray-300" placeholder="Product">
        <input x-model="objective" class="w-full rounded-md border-gray-300" placeholder="Objective">
        <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Generate variations</button>
    </div>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection
