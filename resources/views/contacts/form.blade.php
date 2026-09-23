@extends('layouts.app')
@section('page-title', 'Add Contact')
@section('content')
<form method="POST" action="{{ route('main.email-marketing.contacts.store', ['organizationId' => $organizationId]) }}" class="max-w-xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <input name="email" type="email" required placeholder="Email" class="w-full rounded-md border-gray-300">
    <div class="grid grid-cols-2 gap-3">
        <input name="first_name" placeholder="First name" class="rounded-md border-gray-300">
        <input name="last_name" placeholder="Last name" class="rounded-md border-gray-300">
    </div>
    <input name="company" placeholder="Company" class="w-full rounded-md border-gray-300">
    <select name="contact_list_ids[]" multiple class="w-full rounded-md border-gray-300">
        @foreach($lists as $list)
            <option value="{{ $list->id }}">{{ $list->name }}</option>
        @endforeach
    </select>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save</button>
</form>
@endsection
