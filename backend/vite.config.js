import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
              'resources/css/app.css', 
              'resources/js/app.js',
              'resources/js/selfmade/script.js',
              'resources/js/selfmade/stopwatch.js',
              'resources/js/selfmade/csv_download.js',
              'resources/js/selfmade/reaction.js',
              'resources/js/selfmade/game/panel.js',
              'resources/js/selfmade/guest/password_validate.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,  // ← console.log系を削除
                drop_debugger: true, // ← debuggerも削除
            },
        },
    },
});
