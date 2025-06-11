import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';

export class AdminSideTabs {

	isActiveTab = '';
	// queryParam = 'active-tab';
	// url_params = null;

	constructor(){
		// if(typeof window != 'undefined'){
		// 	this.url_params = new URLSearchParams(window.location.search);
		// }
	}

	// getParam(key){
	// 	let val = null;
	// 	if(this.url_params){
	// 		val = this.url_params.get(this.queryParam);
	// 	}
	// 	return val;
	// }

	// setDefaultTab(defaultTab){
	// 	this.defaultTab = defaultTab;
	// 	return this;
	// }

	// setQueryParam(queryParam){
	// 	this.queryParam = queryParam;
	// 	return this;
	// }

	// isActiveTab(value){
	// 	let inUrlActiveTab = this.getParam();
		
	// 	console.log('isActiveTab isActiveTab', inUrlActiveTab);
	// 	if(inUrlActiveTab){
	// 		return inUrlActiveTab == value;
	// 	}
	// 	else {
	// 		return this.defaultTab == value;
	// 	}
	// }

	setActiveTab(value){
		this.isActiveTab = value;
		// this.url_params.set(this.queryParam, value);
		// console.log(this.url_params);
		// if(typeof route.params[this.queryParam] != 'undefined') {
		// 	return route.params[this.queryParam] = value;
		// }
		// else{
		// 	return this.defaultTab = value;
		// }
	}
}

export const adminSideTabs = reactive(new AdminSideTabs());