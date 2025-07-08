import { reactive } from 'vue';
import { getAdminUrl, base64EncodeUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common';

export class AdminSideTabs {

	isActiveTab = '';
	activeParents = {};

	/**
	 * @param {*} key string identifier of an active tab
	 */
	setActiveTab(key){
		this.isActiveTab = key;
	}

	/**
	 * 
	 * @param {*} key string identifier of the parent active tab
	 */
	setActiveParent(key){
		if(typeof this.activeParents[key] == 'undefined') {
			this.activeParents[key] = true;
		}
		else {
			if(this.activeParents[key] == true){
				this.activeParents[key] = false;
			}
			else {
				this.activeParents[key] = true;
			}
		}
	}

	getAdminUrl(path){
		let params = {};

		if(this.isActiveTab){
			params['active_tab'] = this.isActiveTab
		}

		let activeParents = [];
		Object.keys(this.activeParents).forEach((val) => {
			if(this.activeParents[val]) {
				activeParents.push(val);
			}
		});
		if(activeParents.length){
			params['active_parents'] = base64EncodeUrl(activeParents.join(','));
		}
		return getAdminUrl(path, params);
	}
	
	reset(){
		this.isActiveTab = '';
		this.activeParents = {};
	}
}

export const adminSideTabs = reactive(new AdminSideTabs());