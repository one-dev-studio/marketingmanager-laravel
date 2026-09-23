@extends('layouts.app')
@section('page-title', 'Predictive')
@section('content')
<div class="space-y-4" x-data="{ campaignId: '', output: '', async forecast() {
    const base = @json(url('/main/'.$organizationId.'/analytics/predictions'));
    const { data } = await axios.post(`${base}/campaigns/${this.campaignId}`);
    this.output = JSON.stringify(data.data || data, null, 2);
}}">
    <h1 class="text-2xl font-semibold">Predictive analytics</h1>
    <select x-model="campaignId" class="rounded-md border-gray-300">
        <option value="">Campaign</option>
        @foreach($campaigns as $campaign)
            <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
        @endforeach
    </select>
    <button @click="forecast" class="bg-blue-600 text-white px-4 py-2 rounded-md">Forecast</button>
    <div class="flex gap-2 text-sm">
        <a class="text-blue-700" href="{{ url('/main/'.$organizationId.'/analytics/predictions/optimal-posting-times') }}">Posting times</a>
        <a class="text-blue-700" href="{{ url('/main/'.$organizationId.'/analytics/predictions/budget-optimization') }}">Budget</a>
    </div>
    <pre class="bg-gray-50 border rounded p-4 text-sm" x-text="output"></pre>
</div>
@endsection
