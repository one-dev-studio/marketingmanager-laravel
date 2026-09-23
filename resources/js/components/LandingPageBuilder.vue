<template>
    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white border rounded-lg p-4 space-y-2">
            <div class="flex gap-2 flex-wrap">
                <button class="border px-2 py-1 text-sm" @click="add('hero')">Hero</button>
                <button class="border px-2 py-1 text-sm" @click="add('text')">Text</button>
                <button class="border px-2 py-1 text-sm" @click="add('cta')">CTA</button>
                <button class="border px-2 py-1 text-sm" @click="add('form')">Form</button>
            </div>
            <div v-for="(b,i) in blocks" :key="i" class="border rounded p-2">
                <input v-model="b.value" class="w-full border rounded px-2 py-1">
            </div>
            <input v-model="seoTitle" class="w-full border rounded px-2 py-1" placeholder="SEO title">
            <input v-model="seoDesc" class="w-full border rounded px-2 py-1" placeholder="SEO description">
            <input v-model="domain" class="w-full border rounded px-2 py-1" placeholder="Custom domain">
            <button class="bg-blue-600 text-white px-4 py-2 rounded" @click="save">Save</button>
        </div>
        <div class="bg-white border rounded-lg p-4" v-html="html"></div>
    </div>
</template>
<script>
export default {
    props: ['organizationId', 'page'],
    data() {
        return {
            blocks: this.page?.page_data?.blocks || [],
            seoTitle: this.page?.seo_settings?.title || '',
            seoDesc: this.page?.seo_settings?.description || '',
            domain: this.page?.custom_domain || '',
        };
    },
    computed: {
        html() {
            return this.blocks.map(b => {
                if (b.type === 'hero') return `<section class="p-8 bg-slate-900 text-white text-3xl">${b.value}</section>`;
                if (b.type === 'cta') return `<p><a class="inline-block bg-blue-600 text-white px-4 py-2 rounded">${b.value || 'Get started'}</a></p>`;
                if (b.type === 'form') return `<form class="space-y-2"><input class="border p-2 w-full" placeholder="Email"><button class="bg-blue-600 text-white px-3 py-1">Submit</button></form>`;
                return `<p>${b.value || ''}</p>`;
            }).join('');
        }
    },
    methods: {
        add(type) { this.blocks.push({ type, value: '' }); },
        async save() {
            await window.axios.put(`/main/${this.organizationId}/landing-pages/${this.page.id}`, {
                html_content: this.html,
                page_data: { blocks: this.blocks },
                seo_settings: { title: this.seoTitle, description: this.seoDesc },
                custom_domain: this.domain,
            });
        }
    }
}
</script>
