<?php
namespace Plugins\Opoink\Liv\Lib\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Plugins\Opoink\Liv\Lib\SystemConfig resetLoadedValue()
 * @method static ?string getValue(string $key)
 * @method static bool isSystemValue(string $key)
 * @method static array assignValue(array $data)
 * @method static void saveSystemConfig(array $data)
 * @method static void deleteByPath(string $key)
 * 
 * @see Plugins\Opoink\Liv\Lib\SystemConfig
 */
class SystemConfig extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Plugins\Opoink\Liv\Lib\SystemConfig';
	}
}
?>