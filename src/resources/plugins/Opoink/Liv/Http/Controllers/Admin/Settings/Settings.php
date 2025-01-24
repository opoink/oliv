<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class Settings extends Controller  {

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Settings/Index', [
			'propsdata' => []
		]);
	}
}
?>