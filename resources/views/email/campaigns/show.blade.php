@extends('layouts.app')
@section('page-title', $campaign->name)
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">{{ $campaign->name }}</h1>
            <p class="text-sm text-gray-500">{{ $campaign->subject }} · {{ $campaign->status }}</p>
        </div>
        <form method="POST" action="{{ route('main.email-marketing.campaigns.send', ['organizationId' => $organizationId, 'emailCampaign' => $campaign]) }}">
            @csrf
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Send now</button>
        </form>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @foreach(['sent_count'=>'Sent','opened_count'=>'Opens','clicked_count'=>'Clicks','unsubscribed_count'=>'Unsubs'] as $key => $label)
            <div class="bg-white border rounded-lg p-4">
                <p class="text-sm text-gray-500">{{ $label }}</p>
                <p class="text-xl font-semibold">{{ $campaign->{$key} }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
