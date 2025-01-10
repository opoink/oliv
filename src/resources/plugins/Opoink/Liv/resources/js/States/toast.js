import { reactive } from 'vue';

export const toast = reactive({

	/**
	 * sample entry of toast
	 * {'msg': 'test', 'type': 'success'}
	 */
	list: [],

	/**
	 * add a new toast
	 * @param message str the message to be added
	 */
	add(message, type = 'success', timeout = 8000) {
		if(message){
			let m = {
				msg: message,
				type: type
			};
			this.list.push(m);
			if(timeout){
				setTimeout(f =>{
					this.clear(0)
				}, timeout);
			}
		}
	},
 
	/**
	 * remove specific toast in array
	 * @param key number the key of the toast in array
	 */
	clear(key) {
		this.list.splice(key, 1);
	},

	/**
	 * clear all toast
	 */
	clearAll() {
		this.list = [];
	}
});