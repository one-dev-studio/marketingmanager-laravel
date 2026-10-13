@extends('layouts.public')

@section('content')
<section class="legal-page">
    <div class="container legal-page-inner">
        <h1 class="legal-page-title">{{ $heading }}</h1>
        <div class="legal-prose">
            {!! $body !!}
        </div>
    </div>
</section>
@endsection
