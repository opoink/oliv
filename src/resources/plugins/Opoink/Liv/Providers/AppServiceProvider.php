<?php

namespace Plugins\Opoink\Liv\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		Config::set('auth.guards.admin', [
			'driver' => 'session',
			'provider' => 'admins',
		]);
		Config::set('auth.providers.admins', [
			'driver' => 'eloquent',
			'model' => \Plugins\Opoink\Liv\Models\AdminUser::class,
		]);

		Route::aliasMiddleware('adminauth', \Plugins\Opoink\Liv\Http\Middleware\AdminAuthenticated::class);
	}
}
