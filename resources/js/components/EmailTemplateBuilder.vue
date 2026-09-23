<template>
    <div class="grid md:grid-cols-2 gap-4">
        <form @submit.prevent="save" class="bg-white border rounded-lg p-4 space-y-3">
            <input v-model="form.name" class="w-full rounded-md border-gray-300" placeholder="Name" required>
            <input v-model="form.subject" class="w-full rounded-md border-gray-300" placeholder="Subject" required>
            <div class="flex flex-wrap gap-2">
                <button type="button" class="border px-2 py-1 text-sm rounded" @click="addBlock('heading')">Heading</button>
                <button type="button" class="border px-2 py-1 text-sm rounded" @click="addBlock('text')">Text</button>
                <button type="button" class="border px-2 py-1 text-sm rounded" @click="addBlock('button')">Button</button>
                <button type="button" class="border px-2 py-1 text-sm rounded" @click="addBlock('image')">Image</button>
            </div>
            <div v-for="(block, i) in blocks" :key="i" class="border rounded p-2 space-y-1">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>{{ block.type }}</span>
                    <button type="button" @click="blocks.splice(i,1)">Remove</button>
                </div>
                <input v-if="block.type !== 'text'" v-model="block.value" class="w-full border rounded px-2 py-1">
                <textarea v-else v-model="block.value" class="w-full border rounded px-2 py-1" rows="3"></textarea>
            </div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Save template</button>
        </form>
        <div class="bg-white border rounded-lg p-4">
            <h2 class="font-medium mb-2">Preview</h2>
            <div v-html="html" class="prose max-w-none"></div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['organizationId', 'template'],
    data() {
        return {
            form: {
                name: this.template?.name || '',
                subject: this.template?.subject || '',
            },
            blocks: this.template?.html_content ? [{ type: 'text', value: this.template.html_content }] : [],
        };
    },
    computed: {
        html() {
            return this.blocks.map(b => {
                if (b.type === 'heading') return `<h2>${b.value || ''}</h2>`;
                if (b.type === 'button') return `<p><a href="#" style="background:#2563eb;color:#fff;padding:8px 12px;border-radius:6px">${b.value || 'Click'}</a></p>`;
                if (b.type === 'image') return `<p><img src="${b.value || ''}" alt="" style="max-width:100%"></p>`;
                return `<p>${(b.value || '').replace(/\n/g, '<br>')}</p>`;
            }).join('');
        }
    },
    methods: {
        addBlock(type) { this.blocks.push({ type, value: '' }); },
        async save() {
            const payload = { ...this.form, html_content: this.html, text_content: this.blocks.map(b => b.value).join('\n') };
            if (this.template?.id) {
                await window.axios.put(`/main/${this.organizationId}/email-marketing/templates/${this.template.id}`, payload);
            } else {
                await window.axios.post(`/main/${this.organizationId}/email-marketing/templates`, payload);
            }
            window.location = `/main/${this.organizationId}/email-marketing/templates`;
        }
    }
}
</script>
