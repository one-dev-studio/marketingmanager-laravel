@extends('layouts.app')
@section('page-title', 'Automations')
@section('content')
<div class="space-y-4">
    <h1 class="text-2xl font-semibold">Automation rules</h1>
    <p class="text-sm text-gray-600">Simple trigger/action rules. Visual canvas lives under <a class="text-blue-700" href="{{ route('main.workflows.builder', ['organizationId' => $organizationId]) }}">Workflows</a>.</p>
    <form method="POST" action="{{ route('main.automations.store', ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4 grid md:grid-cols-4 gap-2">
        @csrf
        <input name="name" required placeholder="Rule name" class="rounded-md border-gray-300">
        <select name="trigger" class="rounded-md border-gray-300">
            <option value="campaign.published">Campaign published</option>
            <option value="email.sent">Email sent</option>
            <option value="review.received">Review received</option>
        </select>
        <select name="action" class="rounded-md border-gray-300">
            <option value="notify">Notify team</option>
            <option value="create_task">Create task</option>
            <option value="slack">Send Slack</option>
        </select>
        <button class="bg-blue-600 text-white rounded-md">Create</button>
    </form>
    <div class="bg-white border rounded-lg">
        @forelse($rules as $rule)
            <div class="p-4 border-b flex justify-between">
                <div>
                    <p class="font-medium">{{ $rule->name }}</p>
                    <p class="text-xs text-gray-500">{{ json_encode($rule->trigger_conditions) }} → {{ json_encode($rule->actions) }}</p>
                </div>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('main.automations.test', ['organizationId' => $organizationId, 'automationRule' => $rule]) }}">@csrf<button class="text-sm">Test</button></form>
                    <form method="POST" action="{{ route('main.automations.toggle', ['organizationId' => $organizationId, 'automationRule' => $rule]) }}">@csrf<button class="text-sm">{{ $rule->is_active ? 'Pause' : 'Activate' }}</button></form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-500">No rules yet.</div>
        @endforelse
    </div>
</div>
@endsection
