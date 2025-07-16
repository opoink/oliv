import { usePage, router } from '@inertiajs/vue3';

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

/**
 * use this to make a Base64 encoded string URL friendly, 
 * i.e. '+' and '/' are replaced with '-' and '_' also any trailing '=' 
 * characters are removed
 */
const base64EncodeUrl = function(str){
	str = btoa(str);
	return str.split('+').join('-').split('/').join('_').split('=').join('');
}

/**
 * https://stackoverflow.com/questions/2820249/base64-encoding-and-decoding-in-client-side-javascript
 */
const base64DecodeUrl = function(str){
	var s = str;
	var e={},i,b=0,c,x,l=0,a,r='',w=String.fromCharCode,L=s.length;
	var A="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
	for(i=0;i<64;i++){e[A.charAt(i)]=i;}
	for(x=0;x<L;x++){
		c=e[s.charAt(x)];b=(b<<6)+c;l+=6;
		while(l>=8){((a=(b>>>(l-=8))&0xff)||(x<(L-2)))&&(r+=w(a));}
	}
	return r;
}

/**
 * https://stackoverflow.com/questions/7013643/urlencode-from-php-in-javascript
 */
const urlencode = function(str) {
	let newStr = '';
	const len = str.length;

	for (let i = 0; i < len; i++) {
		let c = str.charAt(i);
		let code = str.charCodeAt(i);

		// Spaces
		if (c === ' ') {
			newStr += '+';
		}
		// Non-alphanumeric characters except "-", "_", and "."
		else if (
			(code < 48 && code !== 45 && code !== 46) ||
			(code < 65 && code > 57) ||
			(code > 90 && code < 97 && code !== 95) ||
			(code > 122)) 
		{
			newStr += '%' + code.toString(16);
		}
		// Alphanumeric characters
		else {
			newStr += c;
		}
	}
	return newStr;
}

const urldecode = function(str) {
	let newStr = '';
	const len = str.length;
  
	for (let i = 0; i < len; i++) {
		let c = str.charAt(i);
	
		if (c === '+') {
			newStr += ' ';
		}
		else if (c === '%') {
			const hex = str.substr(i + 1, 2);
			const code = parseInt(hex, 16);
			newStr += String.fromCharCode(code);
			i += 2;
		}
		else {
			newStr += c;
		}
	}
	return newStr;
}

/**
 * 
 * @param string fallback_url 
 */
const backToPrevUrl = function(fallback_url){
	let url = '/';
	if(typeof window != 'undefined'){
		if(!window.url_history.prev_url || window.url_history.prev_url == window.location.href){	
			url = fallback_url;
		}
		else {
			url = window.url_history.prev_url;
		}
	}
	router.visit(url);
}

export {
	debounce,
	bytesToSize,
	isRoleAllowed,
	getAdminUrl,
	base64EncodeUrl,
	base64DecodeUrl,
	urlencode,
	urldecode,
	http_build_query,
	getUrl,
	backToPrevUrl
}