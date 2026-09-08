import path from 'path';
import { readFileSync, existsSync } from 'fs';

const DS = path.sep;
const ROOT = path.dirname(__dirname).split(DS).join('/') + '/';

/** 
 * transform import @Plugin srouce path 
 */
export default function transformFileImport() {
	let theme;

	return {
		name: 'transform-file-import',
		configResolved(config) {
			// Use the same mode-specific and process environment as the application.
			// Keep this per plugin instance, rather than capturing .env at import time.
			theme = config.env.VITE_OLIV_THEME;
		},

		async transform(src, id) {

			let newSrc = '';
			try {
				id = id.split('\\').join('/');
				let paths = id.split(ROOT + 'plugins/');
				if(paths.length == 2){
					let themeFilePath = ROOT + 'theme/' + theme + '/' + paths[1];
	
					let isExist = await existsSync(themeFilePath);
					if(isExist){
						this.addWatchFile(themeFilePath);
						newSrc = await readFileSync( themeFilePath, 'utf8');
					}
				}
			} catch (error) {
				console.error('Error parsing theme file');
				console.error(error);

				newSrc = '';
			}

			if(newSrc != ''){
				src = newSrc;
			}

			
			// let tmpSrc = src.split('@@Plugins@@').join(ROOT+'plugins');
			// src = tmpSrc;
			return {
				code: src,
				map: null, // provide source map if available
			}
		},
	}
}
