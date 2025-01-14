<?php
namespace Opoink\Oliv\Console\Commands;
 
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Database\Console\Migrations\MigrateCommand as LaravelMigrateCommand;
 
class MigrateCommand extends LaravelMigrateCommand implements Isolatable
{

	/**
     * The name and signature of the console command.
     *
     * @var string
     */
	// protected $signature = 'oliv:plugins-migrate {--database= : The database connection to use}
	// 			{--force : Force the operation to run when in production}
	// 			{--path=* : The path(s) to the migrations files to be executed}
	// 			{--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
	// 			{--schema-path= : The path to a schema dump file}
	// 			{--pretend : Dump the SQL queries that would be run}
	// 			{--seed : Indicates if the seed task should be re-run}
	// 			{--seeder= : The class name of the root seeder}
	// 			{--step : Force the migrations to be run so they can be rolled back individually}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the database migrations including plugin migration paths';


	/**
	 * Create a new migration command instance.
	 *
	 * @param  \Illuminate\Database\Migrations\Migrator  $migrator
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(app('migrator'), app('Illuminate\Contracts\Events\Dispatcher'));
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