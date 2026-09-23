@extends('layouts.app')
@section('page-title', 'Contacts')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Contacts</h1>
            <p class="text-sm text-gray-600">Search, lists, subscribe status</p>
        </div>
        <a href="{{ route('main.email-marketing.contacts.create', ['organizationId' => $organizationId]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add</a>
    </div>
    <form class="flex gap-2" method="GET">
        <input name="search" value="{{ $filters['search'] ?? '' }}" class="rounded-md border-gray-300 flex-1" placeholder="Search">
        <select name="status" class="rounded-md border-gray-300">
            <option value="">Status</option>
            @foreach(['active','unsubscribed','bounced'] as $status)
                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <select name="contact_list_id" class="rounded-md border-gray-300">
            <option value="">List</option>
            @foreach($lists as $list)
                <option value="{{ $list->id }}" @selected(($filters['contact_list_id'] ?? '') == $list->id)>{{ $list->name }}</option>
            @endforeach
        </select>
        <button class="border px-3 rounded-md">Filter</button>
    </form>
    <form method="POST" enctype="multipart/form-data" action="{{ route('main.email-marketing.contacts.import', ['organizationId' => $organizationId]) }}" class="bg-white border rounded p-3 flex gap-3 items-center">
        @csrf
        <input type="file" name="file" required>
        <button class="text-sm text-blue-700">Import CSV</button>
    </form>
    <div class="bg-white border rounded-lg overflow-hidden">
        @forelse($contacts as $contact)
            <div class="p-4 border-b flex justify-between">
                <div>
                    <a class="font-medium" href="{{ route('main.email-marketing.contacts.show', ['organizationId' => $organizationId, 'contact' => $contact]) }}">{{ $contact->full_name }}</a>
                    <p class="text-sm text-gray-500">{{ $contact->email }} · {{ $contact->status }} · {{ $contact->contactLists->pluck('name')->join(', ') }}</p>
                    <p class="text-xs text-gray-400">{{ $contact->tags->pluck('tag')->join(', ') }}</p>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No contacts.</div>
        @endforelse
    </div>
    {{ $contacts->links() }}
</div>
@endsection
