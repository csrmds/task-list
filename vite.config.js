import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import NodeGlobalsPolyfillPlugin from '@esbuild-plugins/node-globals-polyfill'
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue()
    ],
    optimizeDeps: {
        esbuildOptions: {
            define: { global: globalThis },
            plugins: [
                NodeGlobalsPolyfillPlugin({
                    process: true,
                    buffer: true
                })
            ]
        }
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js'
        }
    }
});
