import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { resolve } from 'path'

export default defineConfig({
    plugins: [vue()],
    root: 'assets',
    build: {
        outDir: '../public/build',
        manifest: true,
        emptyOutDir: true,
        rollupOptions: {
            input: {
                app: resolve(__dirname, 'assets/app.js'),
            },
        },
    },
})
