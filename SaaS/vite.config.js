import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // écouter toutes les interfaces réseau
        port: 5173,       // ou tout autre port si nécessaire
        hmr: {
            host: '467b-212-234-17-153.ngrok-free.app', // <-- IP de ton PC --- changer lorsque l'adresse IP local du pc change
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
