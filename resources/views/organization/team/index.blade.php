@extends('layouts.app')
@section('page-title', 'Team')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Team</h1>
    <form method="POST" action="{{ route('main.team.invite', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4 flex gap-2">
        @csrf
        <input name="email" type="email" required placeholder="Invite email" class="flex-1 rounded-md border-gray-300">
        <select name="role_id" class="rounded-md border-gray-300">
            @foreach($availableRoles as $role)
                <option value="{{ $role->id }}">{{ $role->name === 'viewer' || $role->name === 'client' ? 'Client' : 'Org Admin' }} ({{ $role->name }})</option>
            @endforeach
        </select>
        <button class="bg-blue-600 text-white px-4 rounded-md">Invite</button>
    </form>
    <div class="bg-white border rounded-lg">
        @foreach($teamMembers as $member)
            <div class="p-4 border-b flex justify-between items-center">
                <div>
                    <p class="font-medium">{{ $member->name }}</p>
                    <p class="text-sm text-gray-500">{{ $member->email }}</p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('main.team.members.update-role', ['organizationId' => $organizationId, 'userId' => $member->id]) }}">
                        @csrf @method('PUT')
                        <select name="role_id" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm">
                            @foreach($availableRoles as $role)
                                <option value="{{ $role->id }}" @selected($member->pivot->role_id == $role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    <form method="POST" action="{{ route('main.team.members.remove', ['organizationId' => $organizationId, 'userId' => $member->id]) }}" onsubmit="return confirm('Remove member?');">
                        @csrf @method('DELETE')
                        <button class="text-red-600 text-sm">Remove</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
