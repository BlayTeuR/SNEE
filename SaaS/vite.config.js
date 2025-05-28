import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command }) => {
    if (command === 'serve') {
        return {
            server: {
                host: '0.0.0.0',
                port: 5173,
                hmr: {
                    host: 'https://3774-212-234-17-153.ngrok-free.app',
                },
            },
            plugins: [
                laravel({
                    input: ['resources/css/app.css', 'resources/js/app.js'],
                    refresh: true,
                }),
            ],
        };
    } else {
        // build config prod (par défaut)
        return {
            plugins: [
                laravel({
                    input: ['resources/css/app.css', 'resources/js/app.js'],
                    refresh: false,
                }),
            ],
        };
    }
});
