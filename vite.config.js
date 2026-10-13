import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import react from '@vitejs/plugin-react';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/public-marketing.css',
                'resources/js/app.js',
                'resources/js/inertia/app.tsx',
                'resources/js/components/LandingPageBuilder.vue',
                'resources/js/components/SurveyBuilder.vue',
                'resources/js/components/EmailTemplateBuilder.vue',
                'resources/js/components/ReportBuilder.vue',
                'resources/js/components/TaskKanban.vue',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        react(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
});
