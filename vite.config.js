import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/parking.css',
                'resources/js/app.js',
                'resources/js/parking.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true, // Helps with some file system issues
            interval: 1000,
        },
    },
    optimizeDeps: {
        force: true, // Force dependency optimization
    },
});