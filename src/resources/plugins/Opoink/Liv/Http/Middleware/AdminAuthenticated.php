<?php

namespace Plugins\Opoink\Liv\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Plugins\Opoink\Liv\Lib\Facades\Event;


/**
 * https://magecomp.com/blog/make-admin-auth-in-laravel-8/
 */
class AdminAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		Event::dispatch('Plugins_Opoink_Liv_Http_Middleware_AdminAuthenticated_handle_before', []);

		if (Auth::guard('admin')->user()) {
			return $next($request);
		}
		if ($request->ajax() || $request->wantsJson()) {
			return response('Unauthorized.', 401);
		} else {
			return redirect(route('admin.login'));
		}
	}
}
