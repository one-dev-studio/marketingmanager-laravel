@extends('layouts.app')
@section('page-title', 'Builder')
@section('content')
<div id="lp-builder">
    <landing-page-builder organization-id="{{ $organizationId }}" :page='@json($page)'></landing-page-builder>
    <form class="mt-4" method="POST" action="{{ route('main.landing-pages.variants.store', ['organizationId' => $organizationId, 'landingPage' => $page]) }}">
        @csrf
        <input name="name" placeholder="Variant name" class="rounded-md border-gray-300">
        <input name="traffic_percentage" type="number" value="50" class="rounded-md border-gray-300 w-24">
        <button class="text-sm text-blue-700">Add variant split</button>
    </form>
</div>
@endsection
