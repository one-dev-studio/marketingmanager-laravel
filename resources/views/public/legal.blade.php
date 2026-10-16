@extends('layouts.public')

@section('content')
<section class="legal-page">
    <div class="container legal-page-inner">
        <p class="legal-page-crumb">Home › {{ $heading }}</p>
        <h1 class="legal-page-title">{{ $heading }}</h1>
        @if (!empty($summary))
            <p class="legal-page-summary">{{ $summary }}</p>
        @endif
        <div class="legal-prose">
            {!! $body !!}
        </div>
        @if (!empty($cta_label) && !empty($cta_url))
            <p class="legal-page-cta">
                <a href="{{ $cta_url }}" class="btn btn-secondary">{{ $cta_label }}</a>
            </p>
        @endif
    </div>
</section>
@endsection
