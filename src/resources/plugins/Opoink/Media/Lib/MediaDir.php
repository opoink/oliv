<?php
namespace Plugins\Opoink\Media\Lib;

class MediaDir {

	protected string $pubDir;

	public function __construct(){
		$this->pubDir = public_path();
	}

	/**
	 * @return string 
	 */
	public function getPubDir(){
		return $this->pubDir;
	}

	/**
	 * @return string 
	 */
	// public function getMediaDir(){
	// 	return $this->mediaDir;
	// }

	public function cleanFilename(string $originalFilename){
		$extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
		$filename = pathinfo($originalFilename, PATHINFO_FILENAME);

		// Clean the filename: allow only letters, numbers, dash, underscore
		$cleanFilename = preg_replace('/[^A-Za-z0-9_-]/', '-', $filename);
		$cleanFilename = preg_replace('/-+/', '-', $cleanFilename); // Replace multiple dashes with one
		$cleanFilename = trim($cleanFilename, '-'); // Remove leading/trailing dashes

		$finalName = $cleanFilename . '.' . $extension;

		return $finalName;
	}

	/**
	 * Generates a clean, unique filename for saving a new file.
	 * Automatically appends an incrementing suffix (e.g., -1, -2, etc.)
	 * to prevent overwriting existing files.
	 *
	 * @return string Unique filename
	 */
	public function getCleanUniqueFilename(string $destinationPath, string $originalFilename) {
		// Separate filename and extension
		$extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
		$filename = pathinfo($originalFilename, PATHINFO_FILENAME);

		// Clean the filename: allow only letters, numbers, dash, underscore
		$cleanFilename = preg_replace('/[^A-Za-z0-9_-]/', '-', $filename);
		$cleanFilename = preg_replace('/-+/', '-', $cleanFilename); // Replace multiple dashes with one
		$cleanFilename = trim($cleanFilename, '-'); // Remove leading/trailing dashes

		$finalName = $cleanFilename . '.' . $extension;
		$counter = 1;

		// Check for existing files and increment suffix
		while (file_exists($destinationPath . '/' . $finalName)) {
			$finalName = $cleanFilename . '-' . $counter . '.' . $extension;
			$counter++;
		}

		return $finalName;
	}
}
?>