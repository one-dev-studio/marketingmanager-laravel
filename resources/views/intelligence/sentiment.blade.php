@extends('layouts.app')
@section('page-title', 'Sentiment')
@section('content')
<div class="space-y-4" x-data="{ text: '', output: '', alerts: [], async run() {
    const { data } = await axios.post(@json(url('/main/'.$organizationId.'/analytics/sentiment')), { text: this.text });
    this.output = JSON.stringify(data.data || data, null, 2);
    const alerts = await axios.get(@json(url('/main/'.$organizationId.'/analytics/sentiment/alerts')));
    this.alerts = alerts.data.data || [];
}}">
    <h1 class="text-2xl font-semibold">Sentiment</h1>
    <textarea x-model="text" rows="5" class="w-full rounded-md border-gray-300" placeholder="Text or paste a URL"></textarea>
    <button @click="run" class="bg-blue-600 text-white px-4 py-2 rounded-md">Analyze</button>
    <pre class="bg-gray-50 border rounded p-4 text-sm" x-text="output"></pre>
</div>
@endsection
