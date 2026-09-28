import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

/**
 * vite.config.js — Pipeline asset lokal (PRD §4.4).
 *
 * Keputusan desain (revisi Tailwind):
 * - Framework CSS: Tailwind CSS v4 via plugin @tailwindcss/vite (diinstal NPM,
 *   di-compile lokal). Bootstrap sudah dilepas dari package.json.
 * - Input tunggal resources/css/app.css + resources/js/app.js; refresh otomatis.
 * - Tanpa plugin font/CDN apa pun agar tidak ada <link> eksternal.
 */
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
