import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': 'bootstrap',
            '~bootstrap-icons': 'bootstrap-icons',
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                // Suppress Sass deprecation warnings from Bootstrap's source
                // (we cannot modify vendor code; these are Bootstrap's own @import
                //  and legacy function usages, not ours).
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function'],
            },
        },
    },
});

