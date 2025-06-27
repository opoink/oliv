<?php
namespace Opoink\Oliv\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see Plugins\Opoink\Liv\Lib\Event
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