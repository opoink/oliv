<?php

namespace Opoink\Oliv\Providers;

use Illuminate\Support\ServiceProvider;
use Opoink\Oliv\Console\Commands\Install as InstallCommand;
// use Opoink\Oliv\Console\Commands\MigrateCommand;
use Opoink\Oliv\Console\Commands\PluginsUpdate;
// use Opoink\Oliv\Console\Commands\RollbackCommand;

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
		if ($this->app->runningInConsole()) {
			$this->commands([
				InstallCommand::class,
				PluginsUpdate::class,
			]);
		}
    }
}
