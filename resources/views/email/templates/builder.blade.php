@extends('layouts.app')
@section('page-title', 'Template Builder')
@section('content')
<div id="email-builder-app"
     data-organization-id="{{ $organizationId }}"
     data-template="{{ json_encode($template) }}">
    <email-template-builder
        organization-id="{{ $organizationId }}"
        :template="{{ json_encode($template) }}"
    ></email-template-builder>
</div>
@endsection
