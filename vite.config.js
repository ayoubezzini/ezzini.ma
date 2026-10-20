import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fontsource } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const port = Number(process.env.VITE_DEV_SERVER_PORT ?? 5176);

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                fontsource('Instrument Sans', {
                    weights: [400, 500, 600],
                    styles: ['normal', 'italic'],
                    preload: [{ weight: 400 }, { weight: 600 }],
                }),
                fontsource('Instrument Serif', {
                    weights: [400],
                    styles: ['normal', 'italic'],
                    preload: [{ weight: 400, style: 'italic' }],
                }),
                fontsource('JetBrains Mono', {
                    weights: [400, 500],
                    preload: false,
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port,
        strictPort: true,
        hmr: { host: 'localhost' },
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**', '**/vendor/**'],
        },
    },
});
