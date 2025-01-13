<?php

namespace Opoink\Oliv\Providers;

use Illuminate\Support\ServiceProvider;
use Opoink\Oliv\Console\Commands\Install as InstallCommand;
// use Opoink\Oliv\Console\Commands\MigrateCommand;
use Opoink\Oliv\Console\Commands\PluginsUpdate;
// use Opoink\Oliv\Console\Commands\RollbackCommand;
use Illuminate\Support\Facades\Blade;
use Tightenco\Ziggy\Ziggy;

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
		$this->publishes([
			__DIR__.'/../config/oliv.php' => config_path('oliv.php'),
		]);

		if ($this->app->runningInConsole()) {
			$this->commands([
				InstallCommand::class,
				PluginsUpdate::class,
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
						if($uri[0] != config('app.vite_admin_url')){
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
}
