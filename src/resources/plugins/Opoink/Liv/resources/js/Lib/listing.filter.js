import { getAdminUrl } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/common.js';
import { router } from '@inertiajs/vue3';

export class ListingFilter {

	isShowFilter = false;

	sortOrder = {
		key: '',
		value: 'desc'
	}

	filterFields = {};

	addFieldToFilter(key, value){
		this.filterFields[key] = value;
	}

	showFilters() {
		if(this.isShowFilter){
			this.isShowFilter = false;
		}
		else {
			this.isShowFilter = true;
		}
	}

	applyFilters(){
		let filters = [];
		
		let obj = JSON.parse(JSON.stringify(this.filterFields));
		Object.keys(obj).forEach(function(key) {
			let value = obj[key];
			if(typeof value == 'object'){
				let rangeValues = {};
				if(value['from']){
					rangeValues['from'] = value['from'];
				}
				if(value['to']){
					rangeValues['to'] = value['to'];
				}
				if(typeof rangeValues.from != 'undefined' || typeof rangeValues.to != 'undefined'){
					filters.push({
						"key": key,
						"values": rangeValues
					});
				}
			}
			else if(typeof value == 'string'){
				if(value){
					filters.push({"key": key, "value": value});
				}
			}
		});



		let queryParams = {
			sort_order: this.sortOrder,
			filters: filters
		};
		let url = getAdminUrl('/hnd_base/admin-users', queryParams);

		router.visit( url );
	}

	setSortOrder(key){
		this.sortOrder.key = key;
		if(this.sortOrder.value == 'asc'){
			this.sortOrder.value = 'desc';
		}
		else {
			this.sortOrder.value = 'asc';
		}
		this.applyFilters();
	}

	removeFilter(){
		setTimeout(() => {
			this.applyFilters();
		}, 100);
	}

	setFilterValues(filters){
		if(filters){
			filters.forEach(filter => {
				if(typeof filter.value != 'undefined'){
					this.filterFields[filter.key] = filter.value;
				}
				if(typeof filter.values != 'undefined'){
					this.filterFields[filter.key] = filter.values;
				}
			});
		}
	}
}