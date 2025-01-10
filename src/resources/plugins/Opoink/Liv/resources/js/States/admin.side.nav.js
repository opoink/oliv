import { reactive } from 'vue';
import jQuery from 'jquery';

export const adminSideNav = reactive({
	activeMenu: '',
	init(){
		jQuery('.side-nav .side-nav-content .links-wrapper ul li a').on('click', () => {
			this.activeMenu = '';
		})
	},
	showActiveMenu(name){
		if(this.activeMenu == name) {
			this.activeMenu = '';
		}
		else {
			this.activeMenu = name;
		}
	},
	isMenuActive($page, name){
		if(this.activeMenu == ''){
			return $page;
		}
		else {
			return this.activeMenu == name;
		}
	}
});