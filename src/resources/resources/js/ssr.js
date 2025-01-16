import { createInertiaApp } from '@inertiajs/vue3'
import createServer from '@inertiajs/vue3/server'
import { renderToString } from '@vue/server-renderer'
import { createSSRApp, h } from 'vue'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Filters from './Plugins/filters';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index';
import PluginPages from './plugin.pages';
import { RegVueGlobalComponents } from './vue.global.components';

createServer(page =>
	createInertiaApp({
		page,
		render: renderToString,
		title: (title) => `${title}`,
		// resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
		resolve: name => {
			let pluginName = `../../plugins/${name}.vue`;
			if(typeof PluginPages[pluginName] == 'undefined'){
				pluginName = `./Pages/${name}.vue`;
			}
			let page = resolvePageComponent(pluginName, PluginPages);
			return page;
		},
		setup({ App, props, plugin }) {
			const app = createSSRApp({ render: () => h(App, props) });
			app.use(plugin);
			app.use(ZiggyVue, {
				...page.props.ziggy,
				location: new URL(page.props.ziggy.location),
			});
			app.use(Filters);
			RegVueGlobalComponents(app);
			return app;
		},
	}),
	process.env.VITE_INERTIA_SSR_PORT || 13714
)