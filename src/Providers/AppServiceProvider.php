<?php

namespace Opoink\Oliv\Providers;

use Illuminate\Support\ServiceProvider;
use Opoink\Oliv\Console\Commands\Install as InstallCommand;

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
			]);
		}
    }
}
