<?php
namespace Plugins\Opoink\Media\Lib;

class ImageResize extends \Gumlet\ImageResize {

	/**
     * Loads image source and its properties to the instanciated object
     *
     * @param string $filename
     * @return \Plugins\Hoa\Common\Lib\ImageResize
     * @throws \Gumlet\ImageResizeException
     */
    public function __construct($filename)
    {
        if ($filename === null || empty($filename) || (substr($filename, 0, 5) !== 'data:' && !is_file($filename))) {
            throw new \Gumlet\ImageResizeException('File does not exist');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if (!$image_info = getimagesize($filename, $this->source_info)) {
            $image_info = getimagesize($filename);
        }

        if (!$image_info) {
            if (strstr(finfo_file($finfo, $filename), 'image') !== false) {
                throw new \Gumlet\ImageResizeException('Unsupported image type');
            }

            throw new \Gumlet\ImageResizeException('Unsupported file type');
        }

        $this->original_w = $image_info[0];
        $this->original_h = $image_info[1];
        $this->source_type = $image_info[2];

        switch ($this->source_type) {
        case IMAGETYPE_GIF:
			try {
            	$this->source_image = imagecreatefromgif($filename);	
			} catch (\Throwable $th) {
				$imageData = file_get_contents($filename);
				$this->source_image = @imagecreatefromstring($imageData);
			}
            break;

        case IMAGETYPE_JPEG:
			try {
            	$this->source_image = $this->imageCreateJpegfromExif($filename);
			} catch (\Throwable $th) {
				$imageData = file_get_contents($filename);
				$this->source_image = @imagecreatefromstring($imageData);
			}

            // set new width and height for image, maybe it has changed
            $this->original_w = imagesx($this->source_image);
            $this->original_h = imagesy($this->source_image);

            break;

        case IMAGETYPE_PNG:
			try {
            	$this->source_image = imagecreatefrompng($filename);	
			} catch (\Throwable $th) {
				$imageData = file_get_contents($filename);
				$this->source_image = @imagecreatefromstring($imageData);
			}
            break;

        case IMAGETYPE_WEBP:
			try {
            	$this->source_image = imagecreatefromwebp($filename);
			} catch (\Throwable $th) {
				$imageData = file_get_contents($filename);
				$this->source_image = @imagecreatefromstring($imageData);
			}
            break;

        case IMAGETYPE_AVIF:
			try {
            	$this->source_image = imagecreatefromavif($filename);
			} catch (\Throwable $th) {
				$imageData = file_get_contents($filename);
				$this->source_image = @imagecreatefromstring($imageData);
			}
            $this->original_w = imagesx($this->source_image);
            $this->original_h = imagesy($this->source_image);
            break;

        case IMAGETYPE_BMP:
			try {
            	$this->source_image = imagecreatefrombmp($filename);
			} catch (\Throwable $th) {
				$imageData = file_get_contents($filename);
				$this->source_image = @imagecreatefromstring($imageData);
			}
            break;

        default:
            throw new \Gumlet\ImageResizeException('Unsupported image type');
        }

        if (!$this->source_image) {
            throw new \Gumlet\ImageResizeException('Could not load image');
        }

        return $this->resize($this->getSourceWidth(), $this->getSourceHeight());
    }

}
?>