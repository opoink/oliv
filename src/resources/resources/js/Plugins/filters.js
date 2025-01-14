import common from "../common";

export default {
	install: (app, options) => {
		app.config.globalProperties.$getAdminUrl = (path='') => {
			const adminUrl = import.meta.env.VITE_ADMIN_URL || 'admin';
			return '/' + adminUrl + '/' + common.ltrim(path, '/');
		}
		app.config.globalProperties.$isActiveTab = (value, defaultvalue='', queryParam='active-tab') => {
			if(typeof route().params[queryParam] != 'undefined') {
				return route().params[queryParam] == value;
			}
			else{
				return defaultvalue == value;
			}
		}

		// app.config.globalProperties.$getRoute = (name, query='') => {
		// 	let routes = options.initialPage.props.routes;
		// 	let foundRoute = routes.find(i => (name == i.name));

		// 	let url = '/';
		// 	if(typeof foundRoute != 'undefined'){
		// 		url = '/' + common.ltrim(foundRoute.url, '/');
		// 		if(query != ''){
		// 			url += '?' + query;
		// 		}
		// 	}
		// 	return url;
		// }
	}
}