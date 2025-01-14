import path from 'path';
import * as config from '../plugins/compiled.plugins.config.json';
import { getComponentName } from './../resources/js/common';

const DS = path.sep;
const ROOT = path.dirname(__dirname).split(DS).join('/') + '/';

export default function vueComponentInjectorPlugin() {
	return {
		name: 'vue-component-injector-plugin',
		transform(src, id) {
			let filePath = id.replace(ROOT, '');
			let filePathArray = filePath.split('/');

			let imports = [];
			if(filePathArray[0] == 'plugins'){
				if(filePath.split('.').pop().toLowerCase() == 'vue'){
					config.default.forEach(plugin => {
						if(typeof plugin.layouts != 'undefined'){
							if(typeof plugin.layouts[filePath] != 'undefined'){
								let layouts = plugin.layouts[filePath];
								layouts.forEach(layout => {
									if(
										typeof layout['data-v-ref'] != 'undefined' && 
										typeof layout['position'] != 'undefined' && 
										typeof layout['component'] != 'undefined'
									){
										let cName = getComponentName(layout.component);

										let layoutData = {
											component_name: cName,
											data_v_ref: layout['data-v-ref'],
											position: layout.position,
											import: "import "+cName+" from '" + ROOT+layout.component + "';",
											attr: ''
										}
										if(typeof layout['attr'] == 'string'){
											layoutData.attr = layout['attr'];
										}
										imports.push(layoutData);
									}
								});
							}
						}
					});
				}
			}
			
			if(imports.length){
				let imps = [];
				let elTags = {};
				imports.forEach(_import => {
					imps.push(_import.import);
					let eltag = "<" + _import.component_name + " " + _import.attr + "></" + _import.component_name + ">";
					let pos = 'component-inject-' + _import.data_v_ref + '-' + _import.position;
					if(typeof elTags[pos] == 'undefined'){
						elTags[pos] = [];
					}
					elTags[pos].push(eltag);
				});

				Object.keys(elTags).forEach((key) => {
					let targetStr = '<!-- '+key+' -->';
					src = src.split(targetStr).join(elTags[key].join("\n"));
				});
				src = src.replace('/** component import on build rollup will be injected here */', imps.join("\n"));
			}

			return {
				code: src,
				map: null, // provide source map if available
			}
		},
	}
  }