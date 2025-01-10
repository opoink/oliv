import { reactive } from 'vue';

export class AdminSideTabs {

	defaultTab = '';
	queryParam='active-tab'

	constructor(){

	}

	setDefaultTab(defaultTab){
		this.defaultTab = defaultTab;
		return this;
	}

	setQueryParam(queryParam){
		this.queryParam = queryParam;
		return this;
	}

	isActiveTab(value, route){
		if(typeof route.params[this.queryParam] != 'undefined') {
			return route.params[this.queryParam] == value;
		}
		else{
			return this.defaultTab == value;
		}
	}

	setActiveTab(value, route){
		if(typeof route.params[this.queryParam] != 'undefined') {
			return route.params[this.queryParam] = value;
		}
		else{
			return this.defaultTab = value;
		}
	}
}

export const adminSideTabs = reactive(new AdminSideTabs());