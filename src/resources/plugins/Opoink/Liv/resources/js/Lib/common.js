import { usePage } from '@inertiajs/vue3';

const debounce = function(func, wait, immediate = false) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) {
				func.apply(context, args);
			}
		};

		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) {
			func.apply(context, args);
		}
	};
}

const bytesToSize = function(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if (bytes == 0) return '0 Byte';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

const isRoleAllowed = function(resource) {
	let page = usePage();

	let adminUser = page.props.auth.admin;
	let rolesResource = adminUser.roles_resource

	if(adminUser['admin_type'] == 'super_admin'){
		/** always return true for super_admin */
		return true;
	}
	else {
		/**
		 * if the resource is an array, then all value should be in the 
		 * admin user role, if 1 value does not exist
		 * simply mean that the user is not allowed to take action
		 */
		if(typeof resource == 'array'){
			let isAllowed = true;
			resource.foreach((value, key) => {
				if(typeof rolesResource[value] == 'undefined'){
					isAllowed = false;
				}
			})
			return isAllowed;
		}
		else {
			return typeof rolesResource[resource] != 'undefined';
		}
	}
}

const getAdminUrl = function(path='/', paramsObj=null){
	let page = usePage();
	return getUrl('/' + page.props.admin_url + path, paramsObj);
}

const getUrl = function(path='/', paramsObj=null){
	let paramsStr = '';
	if(paramsObj){
		paramsStr = '?' + http_build_query(paramsObj);
	}
	return path + paramsStr;
}

const http_build_query = function (obj, num_prefix, temp_key) {
	var output_string = []
	Object.keys(obj).forEach(function (val) {
		var key = val;

		num_prefix && !isNaN(key) ? key = num_prefix + key : ''

		// var key = encodeURIComponent(key.replace(/[!'()*]/g, escape));
		var key = encodeURIComponent(key.replace(/[!'()*]/g));
		temp_key ? key = temp_key + '[' + key + ']' : ''

		if (typeof obj[val] === 'object') {
			var query = http_build_query(obj[val], null, key)
			output_string.push(query)
		}

		else {
			obj[val] = obj[val] + '';
			// var value = encodeURIComponent(obj[val].replace(/[!'()*]/g, escape));
			var value = encodeURIComponent(obj[val].replace(/[!'()*]/g));
			output_string.push(key + '=' + value)
		}
	});
	return output_string.join('&')
}

export {
	debounce,
	bytesToSize,
	isRoleAllowed,
	getAdminUrl
}