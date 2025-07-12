<?php 
/**
* Copyright 2025 oliv (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Oliv\Lib;

class Dirmanager {
	
	/**
	 * @param $path string
	 */
	public function create($path){
		$create = false;
		if(!is_dir($path)){
			if (mkdir($path, 0777, true)){
				$create = true;
			}
		}
		return $create;
	}

	/**
	 * @param $path string
	 */
	public function createDir($path){
		return $this->create($path);
	}

	/**
	 * @param $dirPath string
	 */
	public function deleteDir($dirPath) {
		if (! is_dir($dirPath)) {
			return false;
			/* throw new \InvalidArgumentException("$dirPath must be a directory"); */
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				$this->deleteDir($file);
			} else {
				unlink($file);
			}
		}
		return rmdir($dirPath);
	}
	
	/**
	 * @param $src string
	 * @param $dst string
	 * @param $copyCallback \Closure()
	 */
	public function copyDir($src, $dst, $copyCallback = null) { 
		if(!is_dir($src)){
			throw new \Exception("The path " . $src . " doesn't exist", 500);
		}
		$dir = opendir($src);
		$this->create($dst);
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this->copyDir($src . '/' . $file,$dst . '/' . $file, $copyCallback); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
					if($copyCallback){
						$copyCallback($src . '/' . $file,$dst . '/' . $file);
					}
				} 
			} 
		} 
		closedir($dir); 
	}	
}