import path from 'path';

const DS = path.sep;
const ROOT = path.dirname(__dirname).split(DS).join('/') + '/';

/** 
 * transform import @Plugin srouce path 
 */
export default function transformFileImport() {
	return {
		name: 'transform-file-import',

		transform(src, id) {
			let tmpSrc = src.split('@@Plugins@@').join(ROOT+'plugins');
			src = tmpSrc;
			return {
				code: src,
				map: null, // provide source map if available
			}
		},
	}
}