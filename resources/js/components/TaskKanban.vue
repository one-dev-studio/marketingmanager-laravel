<template>
    <div>
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-semibold">Tasks</h1>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md" @click="showForm = true">New task</button>
        </div>
        <div v-if="showClientFilter" class="mb-3">
            <select v-model="clientFilter" class="rounded-md border-gray-300" @change="load">
                <option value="">All clients</option>
                <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div v-for="col in columns" :key="col.key" class="bg-gray-50 border rounded-lg p-3 min-h-[24rem]"
                 @dragover.prevent @drop="drop(col.key)">
                <h2 class="font-medium mb-3">{{ col.label }}</h2>
                <div v-for="task in byStatus(col.key)" :key="task.id" draggable="true" @dragstart="dragging = task"
                     class="bg-white border rounded p-3 mb-2 cursor-move" @click="open(task)">
                    <p class="font-medium text-sm">{{ task.title }}</p>
                    <p class="text-xs text-gray-500">{{ task.due_date }} · {{ task.assignee?.name }}</p>
                    <span v-if="task.organization" class="text-xs bg-blue-50 text-blue-700 px-1 rounded">{{ task.organization.name }}</span>
                </div>
            </div>
        </div>
        <div v-if="showForm || selected" class="fixed inset-0 bg-black/40 flex items-center justify-center" @click.self="close">
            <form class="bg-white rounded-lg p-6 w-full max-w-lg space-y-3" @submit.prevent="save">
                <input v-model="form.title" required class="w-full border rounded px-3 py-2" placeholder="Title">
                <textarea v-model="form.description" class="w-full border rounded px-3 py-2" placeholder="Description"></textarea>
                <input type="datetime-local" v-model="form.due_date" class="w-full border rounded px-3 py-2">
                <select v-model="form.assignee_id" class="w-full border rounded px-3 py-2">
                    <option value="">Assignee</option>
                    <option v-for="u in members" :key="u.id" :value="u.id">{{ u.name }}</option>
                </select>
                <div v-if="selected" class="text-sm space-y-2 max-h-40 overflow-y-auto">
                    <p class="font-medium">Comments</p>
                    <p v-for="c in selected.comments || []" :key="c.id">{{ c.user?.name }}: {{ c.body || c.comment }}</p>
                    <input v-model="comment" class="w-full border rounded px-2 py-1" placeholder="Add comment" @keyup.enter="addComment">
                </div>
                <div class="flex gap-2">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
                    <button type="button" @click="close">Close</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        organizationId: [String, Number],
        agencyId: [String, Number],
        showClientFilter: { type: Boolean, default: false },
        clients: { type: Array, default: () => [] },
        members: { type: Array, default: () => [] },
        apiBase: { type: String, default: '' },
    },
    data() {
        return {
            columns: [
                { key: 'todo', label: 'To Do' },
                { key: 'in_progress', label: 'In Progress' },
                { key: 'completed', label: 'Done' },
            ],
            tasks: [],
            dragging: null,
            showForm: false,
            selected: null,
            comment: '',
            clientFilter: '',
            form: { title: '', description: '', due_date: '', assignee_id: '', status: 'todo' },
        };
    },
    mounted() { this.load(); },
    methods: {
        byStatus(status) {
            return this.tasks.filter(t => {
                if (status === 'in_progress') return ['in_progress', 'review'].includes(t.status);
                return t.status === status || (status === 'todo' && t.status === 'pending');
            });
        },
        async load() {
            const url = this.apiBase || `/main/${this.organizationId}/tasks`;
            const { data } = await window.axios.get(url, { params: { organization_id: this.clientFilter || undefined } });
            this.tasks = data.data || data;
        },
        drop(status) {
            if (!this.dragging) return;
            window.axios.post(`/main/${this.dragging.organization_id || this.organizationId}/tasks/${this.dragging.id}/status`, { status });
            this.dragging.status = status;
            this.dragging = null;
        },
        open(task) {
            this.selected = task;
            this.form = { title: task.title, description: task.description, due_date: task.due_date, assignee_id: task.assignee_id, status: task.status };
            this.showForm = true;
        },
        close() { this.showForm = false; this.selected = null; },
        async save() {
            if (this.selected) {
                await window.axios.put(`/main/${this.organizationId}/tasks/${this.selected.id}`, this.form);
            } else {
                await window.axios.post(`/main/${this.organizationId}/tasks`, this.form);
            }
            this.close();
            this.load();
        },
        async addComment() {
            if (!this.selected || !this.comment) return;
            await window.axios.post(`/main/${this.organizationId}/tasks/${this.selected.id}/comments`, { body: this.comment, comment: this.comment });
            this.comment = '';
            this.load();
        }
    }
}
</script>
