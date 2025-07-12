<?php
namespace Plugins\Opoink\Liv\Lib\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Plugins\Opoink\Liv\Lib\Event getEvents()
 * @method static \Plugins\Opoink\Liv\Lib\Event dispatch(string $eventName, array $data = [])
 * 
 * @see Plugins\Opoink\Liv\Lib\Event
 */
class Event extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Plugins\Opoink\Liv\Lib\Event';
	}
}
?>