import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/app.css',
                'resource/css/nav.css',
                'resources/css/high-contrast.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
