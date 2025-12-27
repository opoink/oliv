import { getAdminUrl } from "@@Plugins@@/Opoink/Liv/resources/js/Lib/common";
import { cloneObj } from "./common";
import { loader } from "@@Plugins@@/Opoink/Liv/resources/js/States/loader";
import { toast } from "@@Plugins@@/Opoink/Liv/resources/js/States/toast";

export default class bookmark {

	/**
	 * bookmark data
	 */
	propsdata = {};

	/**
	 * bookmark columns
	 */
	config_cols = {};

	/**
	 * All column that is loaded from the controller
	 */
	on_load_columns = [];

	drop_down_columns = [];

	/**
	 * 
	 * @param {*} propsdata 
	 */
	constructor(
		propsdata
	){
		this.propsdata = propsdata;
		this.setConfigColumns();
	}

	/**
	 * set the bookmark columns
	 * @return {void}  
	 */
	setConfigColumns() {
		if(this.propsdata.bookmark){
			this.config_cols = cloneObj(this.propsdata.bookmark.config.current.columns);

			let excludeKeys = ['ids']
			this.drop_down_columns = Object.entries(this.propsdata.bookmark.config.current.positions)
			.filter(([key]) => !excludeKeys.includes(key))
			.sort((a, b) => a[1] - b[1]) // sort by value
			.map(([key]) => key);        // extract the keys only

			this.on_load_columns = cloneObj(this.drop_down_columns);
		}
	}

	/**
	 * Set the visible column for the listing
	 * @return {void} 
	 */
	setColumns(){
		let newColumns = [];
		this.on_load_columns.forEach(column => {
			if(typeof this.config_cols[column] != 'undefined'){
				if(this.config_cols[column].visible){
					newColumns.push(column);
				}
			}
		});
		this.propsdata.listing.columns = newColumns;
	}

	/**
	 * Returns the column label for the given attribute code.
	 *
	 * @param {string} column - The column attribute code.
	 * @return {string}
	 */
	getColumnLabel(column){
		try {
			let col = this.propsdata.bookmark.config.current.attributes[column];
			if(typeof col == 'string'){
				return col;
			}
			else {
				return col.frontend_label;
			}
		} catch (error) {
			return 'undefined: ' + column;
		}
	}

	/**
	 * Trigger to show or hide columns in the listing
	 * @return {void} 
	 */
	columnShowHide(){
		this.updateBookmarkColumns();
		setTimeout(() => {
			this.setColumns();
		}, 100);
	}

	/**
	 * only update should be here
	 * the controller will generate the default and the current 
	 * bookmark values
	 * 
	 * @return void 
	 */
	updateBookmarkColumns(){
		loader.setLoader(true);

		let jsonData = {
			id: this.propsdata.bookmark.id,
			columns: cloneObj(this.config_cols)
		};

		axios({
			method: 'post',
			url: getAdminUrl('/adminlisting/bookmark/save/visible/columns'),
			data: jsonData
		})
		.then(response => {
			loader.setLoader(false);
		})
		.catch(error => {
			loader.setLoader(false);
			if(typeof error.response.data.message == 'string'){
				toast.add(error.response.data.message, 'danger');
			}
		});
	}

	/**
	 * 
	 * @param {string} countLabel 
	 * @returns {string}
	 */
	countVisibleTrue(countLabel){
		let totalCount = Object.values(this.config_cols).filter(item => item.visible === true).length;
		let totalColumns = this.drop_down_columns.length;

		let label = totalCount + '/' + totalColumns + ' ' + countLabel;
		if(totalCount > 1){
			label += 's';
		}
		return label;
	};

	/**
	 * 
	 * @param {string} column 
	 * @param {*} val 
	 */
	getColumnValue(column, val){
		let col = this.propsdata.bookmark.config.current.columns[column];

		if(typeof col.filter_options != 'undefined'){
			let foundItem = col.filter_options.find(i => (val + '' == i.value + ''));
			return foundItem.label;
		}
		else {
			return val;
		}
	}
}