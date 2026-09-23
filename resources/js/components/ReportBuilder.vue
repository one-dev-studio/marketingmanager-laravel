<template>
    <div class="grid md:grid-cols-2 gap-4">
        <form @submit.prevent="save" class="bg-white border rounded-lg p-4 space-y-3">
            <input v-model="form.name" required class="w-full border rounded px-3 py-2" placeholder="Report name">
            <select v-model="form.type" class="w-full border rounded px-3 py-2">
                <option value="overall">Overall</option>
                <option value="campaign">Campaign</option>
                <option value="email">Email</option>
                <option value="social_media">Social</option>
                <option value="custom">Custom</option>
            </select>
            <div class="flex gap-2 flex-wrap">
                <button type="button" class="border px-2 py-1 text-sm" @click="addChart('bar')">Bar</button>
                <button type="button" class="border px-2 py-1 text-sm" @click="addChart('line')">Line</button>
                <button type="button" class="border px-2 py-1 text-sm" @click="addChart('pie')">Pie</button>
            </div>
            <div v-for="(w,i) in widgets" :key="i" class="border rounded p-2 text-sm">{{ w.type }} chart</div>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </form>
        <div class="bg-white border rounded-lg p-4">
            <canvas ref="chart"></canvas>
        </div>
    </div>
</template>
<script>
import { Chart } from 'chart.js/auto';
export default {
    props: ['organizationId', 'report'],
    data() {
        return {
            form: { name: this.report?.name || '', type: this.report?.type || 'overall' },
            widgets: this.report?.config?.widgets || [],
            chart: null,
        };
    },
    mounted() { this.renderChart(); },
    methods: {
        addChart(type) { this.widgets.push({ type }); this.renderChart(); },
        renderChart() {
            if (!this.$refs.chart) return;
            if (this.chart) this.chart.destroy();
            this.chart = new Chart(this.$refs.chart, {
                type: this.widgets[0]?.type || 'bar',
                data: { labels: ['Impressions','Clicks','Conversions'], datasets: [{ data: [120,40,12], backgroundColor: ['#93c5fd','#60a5fa','#2563eb'] }] },
            });
        },
        async save() {
            const payload = { ...this.form, config: { widgets: this.widgets } };
            if (this.report?.id) {
                await window.axios.put(`/main/${this.organizationId}/reports/${this.report.id}`, payload);
            } else {
                await window.axios.post(`/main/${this.organizationId}/reports`, payload);
            }
            window.location = `/main/${this.organizationId}/reports`;
        }
    }
}
</script>
