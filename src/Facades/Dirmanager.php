<?php
namespace Opoink\Oliv\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Opoink\Oliv\Lib\Dirmanager create($path)
 * @method static \Opoink\Oliv\Lib\Dirmanager createDir($path)
 * @method static \Opoink\Oliv\Lib\Dirmanager deleteDir($dirPath)
 * @method static \Opoink\Oliv\Lib\Dirmanager copyDir($src, $dst, $copyCallback = null)
 * 
 * @see Opoink\Oliv\Lib\Dirmanager
 */
class Dirmanager extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Opoink\Oliv\Lib\Dirmanager';
	}
}
?>