<?php
namespace Plugins\Opoink\Media\Lib;

class ImageManager extends MediaDir {

	protected int $storeId = 0;
	protected string $destination;
	protected ?int $width = null;
	protected ?int $height = null;
	protected string $path;
	protected string $realPath = '';
	protected array $info;
	protected ?string $renderType = null;
	protected ?int $cropPosition = null;
	protected array $cropPositions = [
		ImageResize::CROPTOP,
		ImageResize::CROPCENTER,
		ImageResize::CROPBOTTOM,
		ImageResize::CROPLEFT,
		ImageResize::CROPRIGHT,
		ImageResize::CROPTOPCENTER,
	];
	protected bool $allowEnlarge = false;


	public function setDesination(string $destination){
		$this->destination = $this->getPubDir() . '/' . $destination;
		return $this;
	}

	public function setSize(?float $width = null, ?float $height = null, int|string|null $allowEnlarge = null){
		$this->width = $width ? preg_replace("/[^0-9]/", "", $width) : 0;
		$this->height = $height ? preg_replace("/[^0-9]/", "", $height) : 0;
		$this->allowEnlarge = $allowEnlarge == 1 ? true : false;
		return $this;
	}

	/**
	 * Only two directory types are supported: storage and plugins.
	 *
	 * This method first sets the path to the storage directory using
	 * storage_path(), then checks if the directory exists. If it does not,
	 * it attempts to set the path to the plugins directory instead.
	 *
	 * The method then returns the current instance.
	 *
	 * The saveImage() method will throw an error if the resolved path does
	 * not exist. That error is intentionally not handled here.
	 */
	public function setPath(string $path, string $dir = 'private'){
		$this->path	= storage_path('app/'.$dir.'/' . $path);
		if(empty($this->getRealPath())){
			$this->path = base_path('plugins/' . $path);
		}
		return $this;
	}

	public function setRenderType(?string $renderType = null, $cropPosition = null){
		$this->renderType = strtolower($renderType);
		if($this->renderType == 'crop'){
			if(!$cropPosition){
				$cropPosition = ImageResize::CROPCENTER;
			}

			if(!in_array($cropPosition, $this->cropPositions)){
				$cropPosition = ImageResize::CROPCENTER;
			}

			$this->cropPosition = $cropPosition;
		}
		return $this;
	}

	public function getRealPath(){
		$this->info = pathinfo($this->path);
		$ext = $this->info['extension'];

		$targetNoExt = $this->info['dirname'] . '/' . $this->info['filename'];
		/** Find the real image, webp ext might be a placeholder only */
		if(strtolower($ext) == 'webp' && !file_exists($this->path)){
			$exts = ['webp', 'jpg', 'jpeg', 'png'];
			foreach ($exts as $_ext) {
				if(file_exists($targetNoExt . '.' . $_ext)){
					$this->realPath = $targetNoExt . '.' . $_ext;
					break;
				}
			}
		}
		else {
			$this->realPath = $this->path;
		}

		return $this->realPath;
	}

	public function saveImage(){
		$this->getRealPath();

		if(!empty($this->realPath) && file_exists($this->realPath) && is_file($this->realPath)){

			$targetFile = $this->realPath;
			$resize = new ImageResize($targetFile);
			$srcWidth = $this->width ? $this->width : $resize->getSourceWidth();
			$srcHeight = $this->height ? $this->height : $resize->getSourceHeight();

			if($this->renderType == 'crop'){
				$cp = $this->cropPosition;
				if($cp == 'na'){
					$resize->crop($srcWidth, $srcHeight, false);
				}
				else {
					$resize->crop($srcWidth, $srcHeight, $this->allowEnlarge, $cp);
				}
			}
			else {
				$resize->resizeToBestFit($srcWidth, $srcHeight, $this->allowEnlarge);
			}
			
			$destinationFile = $this->destination;
			$destinationFileInfo = pathinfo($destinationFile);

			$destinationFilePath = dirname($destinationFile);

			if(!is_dir($destinationFilePath)){
				mkdir($destinationFilePath, 0777, true);
			}

			$imgType = null;
			if($destinationFileInfo['extension'] == 'webp'){
				$imgType = IMAGETYPE_WEBP;
			}
			$resize->save($destinationFile, $imgType);
			$this->renderImage($destinationFile, $destinationFileInfo['basename']);
		}
		else {
			$this->renderImage($this->info['basename']);
		}
	}

	public function renderNoImage(string $basename){
		$this->renderImage(base_path('plugins/Opoink/Media/resources/images/image-empty.webp'), $basename);
	}

	protected function renderImage(string $targetFile="", string $imgname="") {
		$imageinfo = getimagesize($targetFile);
		$lifetime = 60*60*24*365; // 60 days only - the revision may get incremented quite often
		header('Content-Disposition: inline; filename="'.basename($imgname).'"');
		header('Last-Modified: '. gmdate('D, d M Y H:i:s', filemtime($targetFile)) .' GMT');
		header('Expires: '. gmdate('D, d M Y H:i:s', time() + $lifetime) .' GMT');
		header('Pragma: ');
		header('Cache-Control: public, max-age='.$lifetime.', no-transform');
		header('Accept-Ranges: none');
		header('Content-type: ' . $imageinfo['mime']);
		header('Content-Length: ' . filesize($targetFile));
		@readfile($targetFile);
		exit;
		die;
	}
}
?>