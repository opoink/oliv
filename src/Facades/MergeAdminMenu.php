<?php
namespace Opoink\Oliv\Facades;

use Illuminate\Support\Facades\Facade;

/**
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