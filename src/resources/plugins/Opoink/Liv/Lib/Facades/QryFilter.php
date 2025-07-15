<?php
namespace Plugins\Opoink\Liv\Lib\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Plugins\Opoink\Liv\Lib\QryFilter addFieldMap(string $key, string $mapKey)
 * @method static \Plugins\Opoink\Liv\Lib\QryFilter setFilters(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Builder $qry, array $columns)
 * @method static \Plugins\Opoink\Liv\Lib\QryFilter setSortOrder(\Illuminate\Http\Request $request, \Illuminate\Database\Eloquent\Builder $qry, array $columns)
 * 
 * @see Plugins\Opoink\Liv\Lib\QryFilter
 */
class QryFilter extends Facade {
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Plugins\Opoink\Liv\Lib\QryFilter';
	}
}
?>