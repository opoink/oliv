
export var scriptloader = {
	loaderScripts: {},
	load: function(url, isCss=false) {
		return new Promise((resolve, reject) => {
			if(typeof this.loaderScripts[url] == 'undefined'){
				let elm;
				const cleanup = () => {
					if (typeof this.loaderScripts[url] != 'undefined') {
						delete this.loaderScripts[url];
					}
				};
				const done = () => {
					resolve();
				};
				const error = () => {
					cleanup();
					reject('Failed to load script: ' + url);
				};
	
				this.loaderScripts[url] = url;
				
				if(!isCss){
					elm = document.createElement('script');
					elm.type = 'text/javascript';
					elm.src = url;
				}
				else {
					elm = document.createElement('link');
					elm.href = url;
					elm.rel = 'stylesheet';
				}
	
				
				elm.onload = done;
				elm.onerror = error;
				(document.getElementsByTagName('head')[0] || document.body).appendChild(elm);
			}
			else {
				resolve();
			}
		});
	}
}