<?php

namespace Opoink\Oliv\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

use Opoink\Oliv\Console\Commands\Install as InstallCommand;
use Opoink\Oliv\Console\Commands\MigrateCommand;
use Opoink\Oliv\Console\Commands\PluginsUpdate;
use Opoink\Oliv\Console\Commands\RollbackCommand;
use Illuminate\Support\Facades\Blade;
use Tighten\Ziggy\Ziggy;

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
        Schema::defaultStringLength(191);
		Config::set('database.connections.mysql.engine', 'InnoDB');

		$this->registerMiddlewareToGroup();

		$this->publishes([
			__DIR__.'/../config/oliv.php' => config_path('oliv.php'),
		]);

		if ($this->app->runningInConsole()) {
			$this->commands([
				InstallCommand::class,
				PluginsUpdate::class,
				MigrateCommand::class,
				RollbackCommand::class,
			]);
		}

		if (!$this->app->runningInConsole()) {
			$this->olivRoutes();
		}
    }

	protected function olivRoutes(){
		Blade::directive('olivroutes', function ($expression) {
			$ziggy = new Ziggy($expression);
			$routes = $ziggy->toArray();
			if(isset($routes['routes'])){
				$newRoutes = [];
				foreach ($routes['routes'] as $keyroute => $valueroute) {
					if(isset($valueroute['uri'])){
						$uri = explode('/', $valueroute['uri']);
						if($uri[0] != config('oliv.vite_admin_url')){
							$newRoutes[$keyroute] = $valueroute;
						}
					}
				}
				$routes['routes'] = $newRoutes;
			}
			return "<script type=\"text/javascript\">const Ziggy = ". json_encode($routes) .";</script>";
		});
		Blade::directive('olivroutesadmin', function ($expression) {
			$ziggy = new Ziggy($expression);
			$routes = $ziggy->toArray();
			return "<script type=\"text/javascript\">const Ziggy = ". json_encode($routes) .";</script>";
		});
	}

	protected function registerMiddlewareToGroup(){
		$this->app->make('router')->pushMiddlewareToGroup('web', \Opoink\Oliv\Middleware\HandleInertiaRequests::class);
		$this->app->make('router')->pushMiddlewareToGroup('web', \Opoink\Oliv\Middleware\PageLayout::class);
	}
}
