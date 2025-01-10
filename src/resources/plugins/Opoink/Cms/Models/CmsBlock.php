<?php
namespace Plugins\Opoink\Cms\Models;

class CmsBlock extends \Plugins\Opoink\Liv\Models\Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cms_block';

	/**
	 * @param $identifier string 
	 * return string
	 */
	public static function getDirName($identifier){
		$dirName = ucwords(str_replace('-', ' ', $identifier));
		$dirName = str_replace(' ', '', $dirName);
		return $dirName;
	}

	/**
	 * get the component path
	 * @param $dirName string 
	 * return string
	 */
	public static function getComponentPath($dirName){
		return ROOT.DS.'resources'.DS.'js'.DS.'Cms'.DS.'Blocks'.DS.$dirName;
	}
}
