import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    const port = Number(env.VITE_PORT ?? 5173);
    const devServerUrl = env.VITE_DEV_SERVER_URL ?? `http://localhost:${port}`;
    const hmrHost = env.VITE_HMR_HOST ?? 'localhost';
    const hmrClientPort = Number(env.VITE_HMR_CLIENT_PORT ?? port);

    return {
        plugins: [
            laravel({
                input: ['resources/js/app.ts'],
                ssr: 'resources/js/ssr.ts',
                refresh: true,
            }),
            tailwindcss(),
            wayfinder({
                formVariants: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        server: {
            host: '0.0.0.0',
            port,
            strictPort: true,
            origin: devServerUrl,
            hmr: {
                host: hmrHost,
                clientPort: hmrClientPort,
            },
        },
    };
});
