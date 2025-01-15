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

	protected $isDumpAutoload = false;

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
			$this->addValueToEnv('SESSION_DRIVER', 'file');
			$this->addValueToEnv('CACHE_STORE', 'file');
			$this->addValueToEnv('VITE_ADMIN_URL', '"admin_abc123"');
			$this->addValueToEnv('VITE_INERTIA_SSR_PORT', '13714');

			$this->addPluginsToComposer();

			/** copy the plugins dir */
			$this->info('Copying the plugins directory...');
			$dirManager->copyDir($resouceDir.$ds.'plugins', base_path().$ds.'plugins');
			$this->info('Plugin directory copied successfully.');
	
			/** copy the routes dir */
			$this->info('Copying the routes directory...');
			$dirManager->copyDir($resouceDir.$ds.'routes', base_path().$ds.'routes');
			$this->info('Routes directory copied successfully.');
	
			/** copy the routes dir */
			$this->info('Copying the app directory...');
			$dirManager->copyDir($resouceDir.$ds.'app', base_path().$ds.'app');
			$this->info('App directory copied successfully.');
	
			/** copy the routes dir */
			$this->info('Copying the resources directory...');
			$dirManager->copyDir($resouceDir.$ds.'resources', base_path().$ds.'resources');
			$this->info('Resources directory copied successfully.');

			/** copy the routes dir */
			$this->info('Copying the config directory...');
			$dirManager->copyDir($resouceDir.$ds.'config', base_path().$ds.'config');
			$this->info('Config directory copied successfully.');

			/** copy the vite.config.js*/
			$this->info('Copying the vite.config.js...');
			if(file_exists(base_path().$ds.'vite.config.js')){
				copy(base_path().$ds.'vite.config.js', base_path().$ds.'vite.config.js.bak');
			}
			copy($resouceDir.$ds.'vite.config.js', base_path().$ds.'vite.config.js');
			$this->info('vite.config.js copied successfully.');

			$this->info('Copying the ecosystem.config.js...');
			if(file_exists(base_path().$ds.'ecosystem.config.js')){
				copy(base_path().$ds.'ecosystem.config.js', base_path().$ds.'ecosystem.config.js.bak');
			}
			copy($resouceDir.$ds.'ecosystem.config.js', base_path().$ds.'ecosystem.config.js');
			$this->info('ecosystem.config.js copied successfully.');

			$this->info('Copying the public directory...');
			$dirManager->copyDir($resouceDir.$ds.'public', base_path().$ds.'public');
			$this->info('Public directory copied successfully.');

			$this->info('Copying the vite plugins directory...');
			$dirManager->copyDir($resouceDir.$ds.'viteplugins', base_path().$ds.'viteplugins');
			$this->info('Vite plugins directory copied successfully.');

			/** add dependency to package json */
			$this->addDependencyToPackageJson('@inertiajs/vue3', '2.0.0');
			$this->addDependencyToPackageJson('@tinymce/tinymce-vue', '^6.1.0');
			$this->addDependencyToPackageJson('@vue/server-renderer', '^3.4.19');
			$this->addDependencyToPackageJson('grapesjs', '^0.22.4');
			$this->addDependencyToPackageJson('grapesjs-blocks-basic', '^1.0.2');
			$this->addDependencyToPackageJson('grapesjs-component-countdown', '^1.0.2');
			$this->addDependencyToPackageJson('grapesjs-custom-code', '^1.0.2');
			$this->addDependencyToPackageJson('grapesjs-indexeddb', '^1.0.5');
			$this->addDependencyToPackageJson('grapesjs-parser-postcss', '^1.0.3');
			$this->addDependencyToPackageJson('grapesjs-plugin-export', '^1.0.12');
			$this->addDependencyToPackageJson('grapesjs-plugin-forms', '^2.0.6');
			$this->addDependencyToPackageJson('grapesjs-preset-webpage', '^1.0.3');
			$this->addDependencyToPackageJson('grapesjs-style-bg', '^2.0.2');
			$this->addDependencyToPackageJson('grapesjs-tabs', '^1.0.6');
			$this->addDependencyToPackageJson('grapesjs-tooltip', '^0.1.8');
			$this->addDependencyToPackageJson('grapesjs-touch', '^0.1.1');
			$this->addDependencyToPackageJson('grapesjs-tui-image-editor', '^1.0.2');
			$this->addDependencyToPackageJson('grapesjs-typed', '^2.0.1');
			$this->addDependencyToPackageJson('jquery', '^3.7.1');
			$this->addDependencyToPackageJson('js-base64', '^3.7.7');
			$this->addDependencyToPackageJson('jsdom', '^25.0.1');
			$this->addDependencyToPackageJson('moment', '^2.30.1');
			$this->addDependencyToPackageJson('sortablejs', '^1.15.6');
			$this->addDependencyToPackageJson('tinymce', '^7.6.0');

			$this->addDependencyToPackageJson('@vitejs/plugin-vue', '^5.2.1', 'devDependencies');
			$this->addDependencyToPackageJson('axios', '^1.7.9', 'devDependencies');
			$this->addDependencyToPackageJson('laravel-vite-plugin', '^1.1.1', 'devDependencies');
			$this->addDependencyToPackageJson('sass', '^1.83.0', 'devDependencies');
			$this->addDependencyToPackageJson('vite', '^6.0.5', 'devDependencies');
			
			$this->addDependencyToPackageJson('build:ssr', 'vite build && vite build --ssr', 'scripts');
	
			$this->info('Oliv installed successfully.');

			$this->warn('Please update the file vite.config.js with your configuration from vite.config.js.bak.');
			$this->warn('Please update the file ecosystem.config.js with your configuration from ecosystem.config.js.bak.');
			if($this->isDumpAutoload == 'no'){
				$this->warn('Please run composer dump-autoload manually.');
			}
			$this->warn('Please run npm install manually.');
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}

	protected function addDependencyToPackageJson($name, $version, $type="dependencies"){
		$this->info('Adding '.$name.' to package.json...');
		$packageJson = json_decode(file_get_contents(base_path('package.json')), true);
		$packageJson[$type][$name] = $version;
		file_put_contents(base_path('package.json'), json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		$this->info($name.' added to package.json successfully.');
	}

	protected function addPluginsToComposer(){
		$this->info('Adding plugins to composer autoload...');
		$composer = json_decode(file_get_contents(base_path('composer.json')), true);
		$composer['autoload']['psr-4']['Plugins\\'] = 'plugins/';
		file_put_contents(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

		$this->info('Running composer dump-autoload...');

		$this->isDumpAutoload = $this->choice('Do you want to run composer dump-autoload?', ['yes', 'no'], 'yes');
		if($this->isDumpAutoload == 'yes'){
			$this->info('Running composer dump-autoload...');
			exec('composer dump-autoload');
		}

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