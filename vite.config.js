import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'Modules/Mini/Resources/assets/js/mini.js',
                'Modules/Mini/Resources/assets/js/api/getItems.js',
                'Modules/Mini/Resources/assets/js/api/updateCount.js',
            ],
            refresh: true,
        }),
        vue()
    ],
    build: {
        outDir: 'public/build',
        rollupOptions: {
            output: {
                entryFileNames: 'mini-[name].js',
                assetFileNames: 'mini-[name].[ext]'
            }
        }
    },
    optimizeDeps: {
        include: ['mini.js']
    }
});
