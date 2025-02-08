<?php
namespace Opoink\Oliv\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PageLayout
{
	public function handle(Request $request, Closure $next): Response
    {
		$layout = app('Opoink\Oliv\Lib\Plugin\Layout');
		$layout->createLayoutByPageName($request->route()->getName());

		return $next($request);
    }
}
?>