@extends('layouts.app')
@section('page-title', 'Create Email Campaign')
@section('content')
<form method="POST" action="{{ route('main.email-marketing.campaigns.store', ['organizationId' => $organizationId]) }}" class="max-w-2xl bg-white border rounded-lg p-6 space-y-3">
    @csrf
    <input name="name" required placeholder="Name" class="w-full rounded-md border-gray-300">
    <input name="subject" required placeholder="Subject" class="w-full rounded-md border-gray-300">
    <input name="from_email" type="email" required placeholder="From email" class="w-full rounded-md border-gray-300">
    <input name="from_name" placeholder="From name" class="w-full rounded-md border-gray-300">
    <select name="email_template_id" class="w-full rounded-md border-gray-300">
        <option value="">Template</option>
        @foreach($templates as $template)
            <option value="{{ $template->id }}">{{ $template->name }}</option>
        @endforeach
    </select>
    <select name="contact_list_ids[]" multiple required class="w-full rounded-md border-gray-300">
        @foreach($lists as $list)
            <option value="{{ $list->id }}">{{ $list->name }}</option>
        @endforeach
    </select>
    <input type="datetime-local" name="scheduled_at" class="w-full rounded-md border-gray-300">
    <label class="text-sm"><input type="checkbox" name="settings[ab_testing][enabled]" value="1"> Enable A/B subject test</label>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Create</button>
</form>
@endsection
