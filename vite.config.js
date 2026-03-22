import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/dt.css',
                'resources/css/kanban.css',
                'resources/assets/css/demo.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
