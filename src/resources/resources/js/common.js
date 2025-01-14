export default {

	trim(str, chr) {
		var rgxtrim = (!chr) ? new RegExp('^\\s+|\\s+$', 'g') : new RegExp('^'+chr+'+|'+chr+'+$', 'g');
		return str.replace(rgxtrim, '');
	},

	rtrim(str, chr) {
		var rgxtrim = (!chr) ? new RegExp('\\s+$') : new RegExp(chr+'+$');
		return str.replace(rgxtrim, '');
	},

	ltrim(str, chr) {
		var rgxtrim = (!chr) ? new RegExp('^\\s+') : new RegExp('^'+chr+'+');
		return str.replace(rgxtrim, '');
	}
}



/**
 * https://stackoverflow.com/questions/2332811/capitalize-words-in-string/7592235#7592235
 * Capitalizes first letters of words in string.
 * @param {string} str String to be modified
 * @param {boolean=false} lower Whether all other letters should be lowercased
 * @return {string}
 * @usage
 *   capitalize('fix this string');     // -> 'Fix This String'
 *   capitalize('javaSCrIPT');          // -> 'JavaSCrIPT'
 *   capitalize('javaSCrIPT', true);    // -> 'Javascript'
 */
const capitalize = function(str, lower = false) {
	return (lower ? str.toLowerCase() : str).replace(/(?:^|\s|["'([{])+\S/g, match => match.toUpperCase());
}

const getComponentName = function(path){
	let cName = path.split('/');

	cName.splice(0, 1);
	cName.splice(2, 1);
	cName.splice(2, 1);
	cName.splice(2, 1);
	cName = cName.join(" ");

	cName = cName.split('.');
	cName.splice(cName.length - 1, 1);
	cName = capitalize(cName[0]);

	cName = cName.split(' ').join("");

	return cName;
}

export {
	getComponentName
}