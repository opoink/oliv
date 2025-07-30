<?php
namespace Opoink\Oliv\Console\Scheduling;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Debug\ExceptionHandler;

class ScheduleRunCommand extends \Illuminate\Console\Scheduling\ScheduleRunCommand
{
	/**
	 * Overrides Laravel's default schedule:run behavior
	 * to include and execute scheduled tasks defined by plugins.
	 * Execute the console command.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
	 * @param  \Illuminate\Contracts\Cache\Repository  $cache
	 * @param  \Illuminate\Contracts\Debug\ExceptionHandler  $handler
	 * @return void
	 */
	public function handle(Schedule $schedule, Dispatcher $dispatcher, Cache $cache, ExceptionHandler $handler)
	{
		$pluginsConfig = getPluginsConfig();
		$allPlugins = $pluginsConfig->getPlugins();
		foreach ($allPlugins as $plugin) {
			$pluginDir = getPluginDir($plugin);
			$targetDir = $pluginDir.DS.'routes'.DS;
			$targetFile = $targetDir.'console.php';

			if(file_exists($targetFile)){
				include($targetFile);
			}
		}

		parent::handle($schedule, $dispatcher, $cache, $handler);
	}
}

?>