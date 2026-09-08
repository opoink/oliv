<?php
namespace Plugins\Opoink\Media\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Opoink\Oliv\Lib\DataObject;

class MediaCacheImage extends Controller {


	/**
	 * Sample URL format:
	 *
	 * /m/c/image/width-70/height-70/render_type-crop/crop_position-2/p/uploads/images/users/1/841bd03e-6ced-4caa-b990-902e916a49aa.png
	 *
	 * Breakdown:
	 *
	 * /m/c/image
	 * The base image path relative to the public directory.
	 *
	 * /width-70
	 * Specifies the width of the rendered image.
	 *
	 * /height-70
	 * Specifies the height of the rendered image.
	 *
	 * /render_type-crop
	 * Specifies the image rendering type. Currently, only "crop" and "best fit"
	 * are supported. If any other value is provided, it will fall back to "best fit".
	 *
	 * /crop_position-2
	 * Specifies the crop position:
	 * CROPTOP        = 1
	 * CROPCENTER     = 2
	 * CROPBOTTOM     = 3
	 * CROPLEFT       = 4
	 * CROPRIGHT      = 5
	 * CROPTOPCENTER  = 6
	 * 
	 * /allow_enlarge-1
	 * Specifies whether image enlargement is allowed.
	 *
	 * Note: This does not enhance or increase the image quality. If the original
	 * image is smaller than the specified width and height, it will only be
	 * enlarged to match the requested dimensions.
	 *
	 * /p
	 * Indicates that the following path is relative to either the storage directory
	 * or the plugins directory.
	 *
	 * The file extension can be the original file extension or ".webp".
	 * If ".webp" is specified, the system will still look for the original file,
	 * but only JPG, JPEG, and PNG files are supported.
	 */
	public function renderImage(Request $request, $path){


		// 1) Split into render options & image path
		$pathOptions = explode('/p/', $path, 2);
		if(count($pathOptions) != 2){
			abort(404);
		}

		$renderOptionsStr = $pathOptions[0];
		$imagePath = $pathOptions[1];

		// 2) Break render options into individual parts
		$options = explode('/', $renderOptionsStr);

		$renderConfig = [];
		foreach ($options as $option) {
			if (strpos($option, '-') !== false) {
				// Split by first "-" only
				list($key, $value) = explode('-', $option, 2);
				// Normalize key (replace "-" with "_")
				$key = str_replace('-', '_', $key);
				$renderConfig[$key] = $value;
			}
		}

		$renderConfig = new DataObject($renderConfig);
		$imageManager = new \Plugins\Opoink\Media\Lib\ImageManager();
		try {
			$imageManager->setDesination('m/c/image/' . $path);

			$imageManager->setSize($renderConfig->getWidth(), $renderConfig->getHeight(), $renderConfig->getAllowEnlarge());
			$imageManager->setPath($imagePath);
			$imageManager->setRenderType($renderConfig->getRenderType(), $renderConfig->getCropPosition());
			$imageManager->saveImage();
		} catch (\Throwable $th) {
			dd($th->getMessage());
			$imageManager->renderNoImage("empty");
		}
	}

}

?>