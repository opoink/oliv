<?php
namespace Plugins\Opoink\Liv\Lib\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Plugins\Opoink\Liv\Lib\AdminListing setTargetDefaultBookmark(string $targetFile)
 * @method static \Plugins\Opoink\Liv\Lib\AdminListing setNamespace(string $namespace)
 * @method static string getNamespace()
 * @method static Array getCollumns()
 * @method static getPaginator(string $model, ?callable $callback = null)
 * @method static \Plugins\Opoink\Liv\Models\ListingBookmark updateBookmark()
 * @method static string getInertiaVersion()
 * @method static \Plugins\Opoink\Liv\Models\ListingBookmark getBookmark()
 * @method static \Plugins\Opoink\Liv\Models\ListingBookmark saveVisibleColumns(int $id, array $columns)
 * 
 * @see \Plugins\Opoink\Liv\Lib\AdminListing
 */
class AdminListing extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Plugins\Opoink\Liv\Lib\AdminListing';
	}
}
?>