<?php
namespace Opoink\Oliv\Console\Commands;

use Illuminate\Console\Command;

class Install extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'oliv:install';
	
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $description = 'Install Oliv';

	/**
	 * Execute the console command.
	 */
	public function handle(): void
	{
		$this->info('Installing Oliv...');

		$dirManager = new \Opoink\Oliv\Lib\Dirmanager();

		$ds = DIRECTORY_SEPARATOR;
		$resouceDir = base_path('vendor'.$ds.'opoink'.$ds.'oliv'.$ds.'src'.$ds.'resources');

		try {
			/** copy the plugins dir */
			$this->info('Copying the plugins directory...');
			$dirManager->copyDir($resouceDir.$ds.'plugins', base_path().$ds.'plugins');
			$this->info('Plugin directory copied successfully.');
	
			// /** copy the routes dir */
			$this->info('Copying the routes directory...');
			$dirManager->copyDir($resouceDir.$ds.'routes', base_path().$ds.'routes');
			$this->info('Routes directory copied successfully.');
	

	
	
	
	
			$this->info('Oliv installed successfully.');
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}


}
?>