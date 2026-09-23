@extends('layouts.admin')
@section('content')
<div class="space-y-3">
    <h1 class="text-2xl font-bold">{{ $package->name }}</h1>
    <p>{{ $package->billing_cycle }} · {{ $package->price }}</p>
    <a href="{{ route('admin.packages.edit', $package) }}" class="text-blue-700">Edit</a>
</div>
@endsection
