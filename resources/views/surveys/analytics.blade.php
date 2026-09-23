@extends('layouts.app')
@section('page-title', 'Survey analytics')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">{{ $survey->title }}</h1>
    <pre class="bg-white border rounded p-4 text-sm">{{ json_encode($analytics, JSON_PRETTY_PRINT) }}</pre>
</div>
@endsection
