@extends('layouts.app')
@section('page-title', 'Page analytics')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">{{ $page->name }} analytics</h1>
    <p class="text-sm text-gray-500">Events: {{ $page->analytics->count() }} · Variants: {{ $page->variants->count() }}</p>
    <ul class="bg-white border rounded-lg divide-y">
        @foreach($page->analytics as $row)
            <li class="p-3 text-sm">{{ $row->event ?? $row->metric ?? 'hit' }} · {{ $row->created_at }}</li>
        @endforeach
    </ul>
</div>
@endsection
