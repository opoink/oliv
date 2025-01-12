<?php
namespace App\Console\Commands;
 
use Illuminate\Database\Console\Migrations\RollbackCommand as LaravelRollbackCommand;

class RollbackCommand extends LaravelRollbackCommand
{
    // use ConfirmableTrait;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	// protected $signature = 'oliv:plugins-migrate-rollback';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rollback the last database migration including plugin migration paths';


	/**
	 * Create a new migration command instance.
	 *
	 * @param  \Illuminate\Database\Migrations\Migrator  $migrator
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(app('migrator'));
	}

	/**
	 * Get all of the migration paths.
	 *
	 * @return array
	 */
	protected function getMigrationPaths()
	{
		// Here, we will check to see if a path option has been defined. If it has we will
		// use the path relative to the root of the installation folder so our database
		// migrations may be run for any customized path from within the application.
		if ($this->input->hasOption('path') && $this->option('path')) {
			return collect($this->option('path'))->map(function ($path) {
				return ! $this->usingRealPath()
								? $this->laravel->basePath().'/'.$path
								: $path;
			})->all();
		}

		$paths = $this->migrator->paths();

		$config = getPluginsConfig();
		$plugins = $config->getPlugins();
		foreach ($plugins as $key => $value) {
			$pluginMigrationDir = getPluginDir($value) . DS . 'migrations';
			
			if(is_dir($pluginMigrationDir)){
				$paths[] = $pluginMigrationDir;
			}
		}

		$migrationPath = $this->getMigrationPath();

		$mergedPaths = array_merge(
			$paths, [$migrationPath]
		);

		return $mergedPaths;
	}
}