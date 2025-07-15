import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Filters from './Plugins/filters';
import PluginPages from './plugin.pages';
import { RegVueGlobalComponents } from './vue.global.components';

createInertiaApp({
	title: (title) => `${title}`,
	resolve: name => {
		let pluginName = `../../plugins/${name}.vue`;
		if(typeof PluginPages[pluginName] == 'undefined'){
			pluginName = `./Pages/${name}.vue`;

			if(typeof PluginPages[pluginName] == 'undefined'){
				pluginName = `../../storage/framework/vue/pages/${name}.vue`;
			}
		}
		let page = resolvePageComponent(pluginName, PluginPages);
		return page;
	},
	setup({ el, App, props, plugin }) {	
		const app = createApp({ render: () => h(App, props) })
		app.use(plugin);
		app.use(Filters);
		RegVueGlobalComponents(app);
		app.mixin({
			mounted() {
				const isPage = this.$options.isPage || false;
				if (isPage && typeof window != 'undefined') {
					if(typeof window.url_history == 'undefined'){
						window.url_history = {
							prev_url: null,
							current_url: window.location.href
						}
					}
					else {
						window.url_history.prev_url = window.url_history.current_url + '';
						window.url_history.current_url = window.location.href;
					}
				}
			}
		});
		app.mount(el);
		return app;
	},
})