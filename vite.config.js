import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/sneat.css',
                'resources/js/sneat.js',
                'resources/css/sneat-auth.css',
                'resources/js/sneat-auth.js',
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~boxicons': path.resolve(__dirname, 'node_modules/boxicons'),
            '~jquery': path.resolve(__dirname, 'node_modules/jquery'),
            '~perfect-scrollbar': path.resolve(__dirname, 'node_modules/perfect-scrollbar'),
            '~sweetalert2': path.resolve(__dirname, 'node_modules/sweetalert2'),
        }
    }
});
