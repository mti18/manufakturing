import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default ({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

    return defineConfig({
        server: {
            host: process.env.VITE_HOST,
        },
        plugins: [
            laravel({
                input: "resources/js/app.js",
                refresh: true,
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
        optimizeDeps: {
            esbuildOptions: {
                target: ["es2020", "safari14"],
            },
        },
        build: {
            target: ["es2020", "safari14"],
        },
    });
};
