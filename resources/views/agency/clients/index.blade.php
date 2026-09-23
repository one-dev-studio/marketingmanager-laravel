@extends('layouts.agency')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between">
        <div>
            <h1 class="text-2xl font-semibold">Clients</h1>
            <p class="text-sm text-gray-600">Organizations managed by this agency</p>
        </div>
        <a href="{{ route('agency.clients.create', $agency) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md">Add New Client</a>
    </div>
    <form method="GET" class="flex gap-2">
        <input name="search" value="{{ $filters['search'] ?? '' }}" class="rounded-md border-gray-300 flex-1" placeholder="Search">
        <button class="border px-3 py-2 rounded-md">Search</button>
    </form>
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="text-left p-3">Organization</th>
                <th class="text-left p-3">Users</th>
                <th class="p-3"></th>
            </tr></thead>
            <tbody>
            @forelse($clients as $client)
                <tr class="border-t">
                    <td class="p-3">{{ $client->name }}</td>
                    <td class="p-3">{{ $client->users_count }}</td>
                    <td class="p-3 text-right">
                        <a class="text-blue-700" href="{{ route('agency.clients.show', [$agency, $client->id]) }}">View Organization</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="p-8 text-center text-gray-500">No clients yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $clients->links() }}
</div>
@endsection
