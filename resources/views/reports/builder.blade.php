@extends('layouts.app')
@section('page-title', 'Report builder')
@section('content')
<div id="report-builder-app">
    <report-builder organization-id="{{ $organizationId }}" :report='@json($report)'></report-builder>
</div>
@endsection
