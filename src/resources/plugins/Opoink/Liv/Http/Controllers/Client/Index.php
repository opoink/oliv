<?php
namespace Plugins\Opoink\Liv\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Composer\InstalledVersions;

class Index extends Controller {

	public function __construct(
    ){}

	/**
	 * Handle the incoming request.
	 */
	public function __invoke(Request $request)
	{
		$oliv_version = InstalledVersions::getVersion('opoink/oliv');
		// $laravel_version = InstalledVersions::getVersion('laravel/framework');
		// $inertiajs_version = InstalledVersions::getVersion('inertiajs/inertia-laravel');


		return inertiaRender('Opoink/Liv/resources/js/Pages/Client/Index', [
			'propsdata' => [
				'oliv_version' => $oliv_version,
				// 'laravel_version' => $laravel_version,
				// 'inertiajs_version' => $inertiajs_version
			]
		]);
	}
}
?>