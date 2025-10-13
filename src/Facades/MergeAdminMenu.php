<?php
namespace Opoink\Oliv\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method array mergeByName(array $base, array $append)
 * @method array mergeChildrenRecursively(array $baseChildren, array $appendChildren)
 * @method array removeMarkedEntries(array $array)
 * 
 * @see Opoink\Oliv\Lib\Plugin\MergeAdminMenu
 */
class MergeAdminMenu extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Opoink\Oliv\Lib\Plugin\MergeAdminMenu';
	}
}
?>