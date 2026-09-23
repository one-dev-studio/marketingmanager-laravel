<template>
    <div class="space-y-4">
        <div class="flex gap-2">
            <button class="border px-2 py-1 text-sm" @click="add('text')">Short text</button>
            <button class="border px-2 py-1 text-sm" @click="add('textarea')">Long text</button>
            <button class="border px-2 py-1 text-sm" @click="add('radio')">Multiple choice</button>
            <button class="border px-2 py-1 text-sm" @click="add('rating')">Rating</button>
        </div>
        <div v-for="(q,i) in questions" :key="i" class="bg-white border rounded p-3 space-y-2">
            <input v-model="q.question" class="w-full border rounded px-2 py-1" placeholder="Question">
            <label class="text-sm"><input type="checkbox" v-model="q.is_required"> Required</label>
            <button class="text-xs" @click="questions.splice(i,1)">Remove</button>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded" @click="save">Save survey</button>
    </div>
</template>
<script>
export default {
    props: ['organizationId', 'survey'],
    data() {
        return { questions: this.survey?.questions || [] };
    },
    methods: {
        add(type) { this.questions.push({ question: '', type, is_required: false, options: [], order: this.questions.length }); },
        async save() {
            await window.axios.put(`/main/${this.organizationId}/surveys/${this.survey.id}`, { questions: this.questions });
            for (const q of this.questions) {
                if (!q.id) {
                    await window.axios.post(`/main/${this.organizationId}/surveys/${this.survey.id}/questions`, q);
                }
            }
        }
    }
}
</script>
