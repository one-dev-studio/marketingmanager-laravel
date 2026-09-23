@extends('layouts.public')

@section('content')
<section class="py-16">
    <div class="container" style="max-width: 48rem;">
        <h1 class="section-title" style="text-align:left;margin-bottom:1.5rem;">{{ $heading }}</h1>
        <div class="text-gray-700" style="display:flex;flex-direction:column;gap:1rem;">
            {!! $body !!}
        </div>
    </div>
</section>
@endsection
