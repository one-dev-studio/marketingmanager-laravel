@extends('layouts.agency')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Agency team</h1>
    <form method="POST" action="{{ route('agency.team.store', $agency) }}" class="bg-white border rounded-lg p-4 flex gap-3">
        @csrf
        <input name="user_id" required class="rounded-md border-gray-300" placeholder="User ID">
        <select name="role" class="rounded-md border-gray-300">
            <option value="member">Agency Member</option>
            <option value="admin">Agency Admin</option>
        </select>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Invite / add</button>
    </form>
    <div class="bg-white border rounded-lg divide-y">
        @foreach($teamMembers as $member)
            <div class="p-4 flex justify-between items-start">
                <div>
                    <p class="font-medium">{{ $member->name }}</p>
                    <p class="text-sm text-gray-500">{{ $member->pivot->role ?? 'member' }}</p>
                </div>
                <form method="POST" action="{{ route('agency.team.destroy', [$agency, $member]) }}">@csrf @method('DELETE')<button class="text-red-600 text-sm">Remove</button></form>
            </div>
        @endforeach
    </div>
</div>
@endsection
