<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Index extends Controller {

	public function __construct(
    ){}

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		return inertiaRender('Opoink/Liv/resources/js/Pages/Admin/Index', [
		]);
	}
}
?>