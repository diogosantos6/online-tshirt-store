import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/shoptemplate/app.scss',
                'resources/js/shoptemplate/app.js',
                'resources/sass/admintemplate/app.scss',
                'resources/js/admintemplate/app.js',
            ],
            refresh: true,
        }),
    ],
});
