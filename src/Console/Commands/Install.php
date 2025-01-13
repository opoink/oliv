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
			$this->addPluginsToComposer();

			/** copy the plugins dir */
			$this->info('Copying the plugins directory...');
			$dirManager->copyDir($resouceDir.$ds.'plugins', base_path().$ds.'plugins');
			$this->info('Plugin directory copied successfully.');
	
			// /** copy the routes dir */
			$this->info('Copying the routes directory...');
			$dirManager->copyDir($resouceDir.$ds.'routes', base_path().$ds.'routes');
			$this->info('Routes directory copied successfully.');

			$this->addValueToEnv('SESSION_DRIVER', 'file');
			$this->addValueToEnv('CACHE_STORE', 'file');
			$this->addValueToEnv('VITE_ADMIN_URL', '"admin_abc123"');
			$this->addValueToEnv('VITE_INERTIA_SSR_PORT', '13714');
	
	
	
	
			$this->info('Oliv installed successfully.');
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}

	protected function addPluginsToComposer(){
		$this->info('Adding plugins to composer autoload...');
		$composer = json_decode(file_get_contents(base_path('composer.json')), true);
		$composer['autoload']['psr-4']['Plugins\\'] = 'plugins/';
		file_put_contents(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

		$this->info('Running composer dump-autoload...');
		exec('composer dump-autoload');

		$this->info('Plugins added to composer autoload successfully.');
	}

	protected function addValueToEnv($key, $value){
		$this->info('Adding '.$key.' to .env file...');
		$envFile = base_path('.env');
		$env = file_get_contents($envFile);

		$env = explode("\n", $env);

		$founded = false;
		
		foreach ($env as $k => $v) {
			$keyVal = explode('=', $v);
			if(isset($keyVal[0]) && $keyVal[0] == $key){
				$keyVal[1] = $value;
				$founded = true;
			}

			$newEnv[] = implode('=', $keyVal);
		}

		if(!$founded){
			$newEnv[] = $key.'='.$value;
		}
		
		file_put_contents($envFile, implode("\n", $newEnv));
	}


}
?>