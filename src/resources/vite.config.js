import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueComponentInjectorPlugin from './viteplugins/vue.component.injector.plugin';
import transformFileImport from './viteplugins/import.plugin';
import path from 'path';

export default defineConfig({
    plugins: [
		{
			...transformFileImport(),
			enforce: 'pre'
		},
		vueComponentInjectorPlugin(),
        laravel({
            input: [
				'resources/css/admin.app.scss', 
				'resources/css/client.app.scss', 
				'resources/js/app.js'
			],
			ssr: 'resources/js/ssr.js',
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
	resolve: {
		alias: {
			'Plugins': path.resolve(__dirname, './plugins'),
			'@@Plugins@@': path.resolve(__dirname, './plugins'),
		},
	}
});
