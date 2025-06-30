import path from 'path';
import dotenv from 'dotenv';
import { readFileSync, existsSync } from 'fs';

const DS = path.sep;
const ROOT = path.dirname(__dirname).split(DS).join('/') + '/';
const env = dotenv.config().parsed;

/** 
 * transform import @Plugin srouce path 
 */
export default function transformFileImport() {
	return {
		name: 'transform-file-import',

		async transform(src, id) {

			let newSrc = '';
			try {
				id = id.split('\\').join('/');
				let paths = id.split(ROOT + 'plugins/');
				if(paths.length == 2){
					let themeFilePath = ROOT + 'theme/' + env.VITE_OLIV_THEME + '/' + paths[1];
	
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