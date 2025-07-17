<?php

namespace Opoink\Oliv\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

use Opoink\Oliv\Console\Commands\Install as InstallCommand;
use Opoink\Oliv\Console\Commands\MigrateCommand;
use Opoink\Oliv\Console\Commands\PluginsUpdate;
use Opoink\Oliv\Console\Commands\RollbackCommand;

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
    }

	protected function registerMiddlewareToGroup(){
		$this->app->make('router')->pushMiddlewareToGroup('web', \Opoink\Oliv\Middleware\HandleInertiaRequests::class);
		$this->app->make('router')->pushMiddlewareToGroup('web', \Opoink\Oliv\Middleware\PageLayout::class);
	}
}
