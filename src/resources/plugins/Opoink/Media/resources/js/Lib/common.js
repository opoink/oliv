
/**
 * 
 * @param {*} path path to private storage
 * @param {*} width zero for original width
 * @param {*} height zero for original height
 * @param {*} render_type default is "crop" else fall to best fit
 * @param {*} crop_position CROPTOP 1, CROPCENTER 2, CROPBOTTOM 3, CROPLEFT 4, CROPRIGHT 5. default is "na" 
 * @returns 
 */
const getImageUrl = function(path, width=0, height=0, render_type='crop', crop_position='na', allow_enlarge=0){
	// let p = "/m/c/image/width-"+width+"/height-"+height+"/render_type-"+render_type+"/crop_position-"+crop_position+"/p/";
	let p = "/m/c/image";
	p += "/width-" + width;
	p += "/height-" + height;
	p += "/render_type-" + render_type;
	p += "/crop_position-" + crop_position;
	p += "/allow_enlarge-" + allow_enlarge;
	p += "/p/";

	path = convertToWebpIfNeeded(path);
	return p + path;
}

const convertToWebpIfNeeded = function(filePath) {
	if (filePath.endsWith('.webp')) return filePath;
	return filePath.replace(/\.[^/.]+$/, '.webp');
}

export {
	getImageUrl,
	convertToWebpIfNeeded
}