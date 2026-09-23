@extends('layouts.agency')
@section('content')
<div id="agency-tasks">
    <task-kanban
        organization-id="{{ $agency->id }}"
        api-base="/agency/{{ $agency->id }}/tasks"
        :show-client-filter="true"
        :clients='@json($agency->clientOrganizations()->get(["organizations.id as id","name"]))'
    ></task-kanban>
</div>
@endsection
@push('scripts')
<script type="module">
import { createApp } from 'vue';
import TaskKanban from '/resources/js/components/TaskKanban.vue';
createApp({}).component('task-kanban', TaskKanban).mount('#agency-tasks');
</script>
@endpush
