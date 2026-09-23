@extends('layouts.app')
@section('page-title', 'Press Releases')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Press releases</h1>
    <form method="POST" action="{{ route('main.press-releases.store', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4 space-y-2">
        @csrf
        <input name="title" required placeholder="Title" class="w-full rounded-md border-gray-300">
        <textarea name="content" required rows="4" class="w-full rounded-md border-gray-300" placeholder="Body"></textarea>
        <input type="datetime-local" name="release_date" class="rounded-md border-gray-300">
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
    </form>
    <div class="bg-white border rounded-lg">
        @forelse($pressReleases as $release)
            <div class="p-4 border-b">
                <p class="font-medium">{{ $release->title }}</p>
                <p class="text-sm text-gray-500">{{ $release->status }} · {{ $release->release_date }}</p>
                <div class="flex gap-2 text-sm mt-1">
                    <form method="POST" action="{{ route('main.press-releases.schedule', ['organizationId' => $organizationId, 'pressRelease' => $release]) }}">@csrf<button class="text-blue-700">Schedule</button></form>
                    <form method="POST" action="{{ route('main.press-releases.approve', ['organizationId' => $organizationId, 'pressRelease' => $release]) }}">@csrf<button>Approve</button></form>
                    <form method="POST" action="{{ route('main.press-releases.distribute', ['organizationId' => $organizationId, 'pressRelease' => $release]) }}">@csrf<button>Distribute</button></form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">None yet.</div>
        @endforelse
    </div>
    {{ $pressReleases->links() }}
    <div class="bg-white border rounded-lg p-4">
        <h2 class="font-medium mb-2">Media contacts</h2>
        <form method="POST" action="{{ route('main.press-releases.contacts.store', ['organizationId' => $organizationId]) }}" class="grid md:grid-cols-4 gap-2">
            @csrf
            <input name="name" required placeholder="Name" class="rounded-md border-gray-300">
            <input name="email" type="email" required placeholder="Email" class="rounded-md border-gray-300">
            <input name="media_outlet" placeholder="Outlet" class="rounded-md border-gray-300">
            <button class="text-blue-700">Add</button>
        </form>
        <form class="mt-3" method="POST" enctype="multipart/form-data" action="{{ route('main.press-releases.contacts.import', ['organizationId' => $organizationId]) }}">
            @csrf
            <input type="file" name="file">
            <button class="text-sm">Import</button>
        </form>
    </div>
</div>
@endsection
