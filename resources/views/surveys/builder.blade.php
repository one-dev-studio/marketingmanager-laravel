@extends('layouts.app')
@section('page-title', 'Survey builder')
@section('content')
<div id="survey-builder">
    <survey-builder organization-id="{{ $organizationId }}" :survey='@json($survey)'></survey-builder>
    <p class="text-sm mt-4">Share: <code>{{ url('/s/'.$survey->id) }}</code> · Embed: <code>&lt;iframe src="{{ url('/s/'.$survey->id) }}"&gt;</code></p>
</div>
@endsection
