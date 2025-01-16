import './bootstrap';


import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Filters from './Plugins/filters';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index';
import PluginPages from './plugin.pages';
import { RegVueGlobalComponents } from './vue.global.components';

createInertiaApp({
	title: (title) => `${title}`,
	// resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, PluginPages),
	resolve: name => {
		let pluginName = `../../plugins/${name}.vue`;
		if(typeof PluginPages[pluginName] == 'undefined'){
			pluginName = `./Pages/${name}.vue`;
		}
		let page = resolvePageComponent(pluginName, PluginPages);
		return page;
	},
	setup({ el, App, props, plugin }) {	
		const app = createApp({ render: () => h(App, props) })
		app.use(plugin);
		app.use(ZiggyVue, Ziggy);
		app.use(Filters);
		RegVueGlobalComponents(app);
		app.mount(el);
		return app;
	},
})