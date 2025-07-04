import { reactive } from 'vue';

export class AdminSideTabs {

	isActiveTab = '';
	activeParents = {};

	setActiveTab(value){
		this.isActiveTab = value;
	}

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
}

export const adminSideTabs = reactive(new AdminSideTabs());