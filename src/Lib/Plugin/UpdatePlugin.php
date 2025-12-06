<?php
/**
* Copyright 2025 oliv (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Oliv\Lib\Plugin;

use Opoink\Oliv\Lib\Writer as FileWriter;
use Illuminate\Support\Facades\Cache;
use Opoink\Oliv\Facades\MergeAdminMenu;

class UpdatePlugin {
	
	/**
	 * routes
	 */
	protected $plugin_web = [];
	protected $plugin_api = [];
	protected $compiled_config;
	protected $plugins_config = [];
	protected $plugins_admin_css = [];
	protected $plugins_client_css = [];
	protected $config_files = [];
	protected $admin_menus = [];
	protected $service_providers = [];
	protected $plugin_events = [];

	/**
	 * global components directories
	 */
	protected $vueGlobalComponents = [];

	/**
	 * vue pages
	 */
	protected $vuePages = [
		'./Pages/**/*.vue',
		'../../storage/framework/vue/pages/**/*.vue'
	];

	/**
	 * @var \Opoink\Oliv\Lib\Plugin\MergeSystemConfig
	 */
	protected $mergeSystemConfig;

	public function __construct(
		protected \Opoink\Oliv\Console\Commands\PluginsUpdate $command
	){
		$this->mergeSystemConfig = app(\Opoink\Oliv\Lib\Plugin\MergeSystemConfig::class);
	}


	public function executeUpdate() {

		$pluginsConfig = getPluginsConfig();

		$this->compiled_config = $pluginsConfig;

		$allPlugins = $pluginsConfig->getPlugins();
		foreach ($allPlugins as $plugin) {
			$this->updatedPlugins($plugin);
		}

		$this->saveConfigFiles();
		$this->saveAdminMenu();
		$this->saveProviders();
		$this->compilePluginLayout();
		$this->compilePluginCss();
		$this->savePluginVuePages();
		$this->saveVueGlobalComponents($this->vueGlobalComponents);
		$this->savePluginRoutes('plugin_web');
		// $this->savePluginRoutes('plugin_api');
		$this->mergeSystemConfig->merge();

		$this->saveCollectedEvents();
	}

	/**
	 * @param $plugin string 
	 */
	protected function updatedPlugins($plugin){
		$this->command->info('Start updating plugin: ' . $plugin);

		$pluginDir = getPluginDir($plugin);

		$this->collectConfigFiles($plugin);
		$this->collectProviders($plugin);
		$this->collectEvents($plugin);


		$pluginConfig = $pluginDir.DS.'config.json';
		if(file_exists($pluginConfig) && is_file($pluginConfig) ){
			$this->plugins_config[] = json_decode(file_get_contents($pluginConfig), true);
		}

		$pluginCss = $pluginDir.DS.'resources'.DS.'css'.DS;
		$pluginCss = str_replace(DS, '/', $pluginCss);
		if(file_exists($pluginCss.'admin.app.scss') && is_file($pluginCss.'admin.app.scss') ){
			$adminAppScssCount = count($this->plugins_admin_css) + 1;
			$this->plugins_admin_css[] = "@use '".$pluginCss.'admin.app'."' as adminaapp".$adminAppScssCount.";";
		}
		if(file_exists($pluginCss.'client.app.scss') && is_file($pluginCss.'client.app.scss') ){
			$clientAppScssCount = count($this->plugins_client_css) + 1;
			$this->plugins_client_css[] = "@use '".$pluginCss.'client.app'."' as clientapp".$clientAppScssCount.";";
		}

		$pluginSystemConfig = $pluginDir.DS.'etc'.DS.'admin'.DS.'system.php';
		if(file_exists($pluginSystemConfig) && is_file($pluginSystemConfig )){
			$this->mergeSystemConfig->addSystemConfig(include($pluginSystemConfig));
		}

		$pluginResource = '../../plugins/' . str_replace('_', '/', $plugin) . '/resources/js';
		$this->vuePages[] = $pluginResource.'/Pages/**/*.vue';

		$this->vueGlobalComponents[] = $pluginDir.DS.'resources'.DS.'js'.DS.'GlobalComponents'.DS;

		$this->pluginRoutes($plugin);

		$this->command->info('End updating plugin: ' . $plugin);
	}

	protected function collectConfigFiles($plugin){
		$pluginDir = getPluginDir($plugin);
		$configDir = $pluginDir.DS.'config';
		if(file_exists($configDir) && is_dir($configDir)){
			$files = scandir($configDir);
			foreach ($files as $file) {
				if($file == '.' || $file == '..'){
					continue;
				}

				$targetFile = $configDir . DS . $file;
				$info = pathinfo($file);

				if($info['filename'] == 'adminmenu'){
					// $this->admin_menus = MergeAdminMenu::mergeMenus($this->admin_menus, include($targetFile));
					$this->admin_menus = MergeAdminMenu::mergeByName($this->admin_menus, include($targetFile));
					// $this->admin_menus[] = include($targetFile);
				}
				else {
					if(!isset($this->config_files[$info['filename']])){
						$this->config_files[$info['filename']] = include($targetFile);
					}
					else {
						$this->config_files[$info['filename']] = array_merge_recursive($this->config_files[$info['filename']], include($targetFile));
						// $this->config_files[$info['filename']] = array_replace_recursive($this->config_files[$info['filename']], include($targetFile));		
					}
				}
			}
		}

		$this->admin_menus = MergeAdminMenu::removeMarkedEntries($this->admin_menus);
	}

	protected function collectProviders($plugin){
		$pluginDir = getPluginDir($plugin);
		$providerDir = $pluginDir.DS.'Providers';
		if(file_exists($providerDir) && is_dir($providerDir)){
			$files = scandir($providerDir);
			foreach ($files as $file) {
				if($file == '.' || $file == '..'){
					continue;
				}

				$info = pathinfo($file);
				$nameSpace = 'Plugins\\' . str_replace('_', '\\', $plugin) . '\\Providers\\' . $info['filename'];

				if(!in_array($nameSpace, $this->service_providers)){
					$this->service_providers[] = $nameSpace;
				}
			}
		}
	}

	protected function collectEvents($plugin){
		$pluginDir = getPluginDir($plugin);
		
		$eventDir =  $pluginDir . DS . 'EventListeners';
		if(is_dir($eventDir)){
			$eventList = $eventDir . DS . 'EventList.php';
			if(file_exists($eventList)){
				$eventList = include($eventList);

				foreach ($eventList as $event) {
					$eventName = $event['name'];
					if(!isset($this->plugin_events[$eventName])){
						$this->plugin_events[$eventName] = [];
					}
					$this->plugin_events[$eventName][] = $event;
				}
			}
		}
	}

	/**
	 * @param $plugin string 
	 */
	protected function pluginRoutes($plugin){
		$pluginDir = getPluginDir($plugin);

		$targetDir = $pluginDir.DS.'routes'.DS;

		$web = $targetDir.'web.php';
		// $api = $targetDir.'api.php';

		if(file_exists($web) && is_file($web)){
			$this->command->info('Web routes: ' . $web);
			$this->plugin_web[] = $web;
		}
		// if(file_exists($api) && is_file($api)){
		// 	$this->command->info('API routes: ' . $api);
		// 	$this->plugin_api[] = $api;
		// }
	}

	protected function saveConfigFiles(){
		$data = '<?php' . PHP_EOL;
		$data .= 'return ' . var_export($this->config_files, true) . PHP_EOL;
		$data .= '?>';
		$w = new FileWriter();
		$w->setDirPath(ROOT.DS.'config');
		$w->setData($data);
		$w->setFilename('plugins');
		$w->setFileextension('php');
		$w->write();
	}

	protected function saveAdminMenu(){
		$data = '<?php' . PHP_EOL;
		$data .= 'return ' . var_export($this->admin_menus, true) . PHP_EOL;
		$data .= '?>';
		$w = new FileWriter();
		$w->setDirPath(ROOT.DS.'config');
		$w->setData($data);
		$w->setFilename('adminmenus');
		$w->setFileextension('php');
		$w->write();
	}

	protected function saveProviders(){
		$providersPath = getPath('bootstrap/providers.php');
		$providers = [];
		if(file_exists($providersPath)){
			$providers = include($providersPath);
		}
		
		foreach ($this->service_providers as $provider) {
			if(!in_array($provider, $providers)){
				$providers[] = $provider;
			}
		}

		$data = '<?php' . PHP_EOL;
		$data .= 'return ' . var_export($providers, true) . PHP_EOL;
		$data .= '?>';	
		$w = new FileWriter();
		$w->setDirPath(ROOT.DS.'bootstrap');
		$w->setData($data);
		$w->setFilename('providers');
		$w->setFileextension('php');
		$w->write();
	}

	/**
	 * @param $routes string
	 */
	protected function savePluginRoutes($routes){

		// $this->command->info('Compiling plugin routes');

		// if(count($this->$routes)){
			foreach ($this->$routes as &$value) {
				$value = str_replace(ROOT, '', $value);
				$value = str_replace(DS, '/', $value);
				$value = 'require_once(getPath("'.$value.'"))';
			}
			$data = '<?php' . PHP_EOL;
			$data .= implode(";" . PHP_EOL, $this->$routes) .';' . PHP_EOL;
			$data .= '?>';
			
			$w = new FileWriter();
			
			$w->setDirPath(getPath('routes'));
			$w->setData($data);
			$w->setFilename($routes);
			$w->setFileextension('php');
			$w->write();
		// }
	}

	protected function savePluginVuePages(){
		
		$this->command->info('Compiling plugin pages');

		$data = "const PluginPages = import.meta.glob([" . PHP_EOL;
			$data .= "\t'" . implode("'," . PHP_EOL . "\t'", $this->vuePages) . "'" . PHP_EOL;
		$data .= "]);" . PHP_EOL;
		$data .= "export default PluginPages;";

		$w = new FileWriter();
		
		$w->setDirPath(getPath('resources/js'));
		$w->setData($data);
		$w->setFilename('plugin.pages');
		$w->setFileextension('js');
		$w->write();
	}

	protected function saveVueGlobalComponents($tartgetDir){
		
		$globalComponents = $this->getVueGlobalComponents($tartgetDir);

		$data = "";
		$names = [];
		foreach ($globalComponents as $globalComponent) {
			$name = getGlobalComponentName($globalComponent);
			$names[] = $name;
			$data .= 'import '.$name.' from "'.$globalComponent.'";' . PHP_EOL;
		}

		$data .= PHP_EOL . PHP_EOL . PHP_EOL;

		$data .= 'const RegVueGlobalComponents = function(app){' . PHP_EOL;
			foreach ($names as $name) {
				$data .= "\tapp.component('".$name."', ".$name.");" . PHP_EOL;
				// $data .= "\t'" . 'app.component("'.$name.'", '.$name.');' . PHP_EOL;
			}
		$data .= '}' . PHP_EOL;

		$data .= "export {RegVueGlobalComponents}";
		
		$w = new FileWriter();
		$w->setDirPath(getPath('resources/js'));
		$w->setData($data);
		$w->setFilename('vue.global.components');
		$w->setFileextension('js');
		$w->write();
	}

	protected function getVueGlobalComponents($tartgetDir, &$vueComponents=[]){
		foreach ($tartgetDir as $targetPath) {
			if($targetPath == '.' || $targetPath == '..'){
				continue;
			}
			if(is_dir($targetPath)){
				$files = glob($targetPath . '*', GLOB_MARK);
				$this->getVueGlobalComponents($files, $vueComponents);
			}
			elseif (is_file($targetPath)){
				$ext = strtolower(substr($targetPath, strrpos($targetPath, '.') + 1));
				if($ext == 'vue'){
					$vueComponents[] = str_replace("\\", "/", $targetPath);
				}
			}
		}
		return $vueComponents;
	}

	

	protected function compilePluginLayout(){
		$this->command->info('Compiling plugin layouts');
		$newCompiledConfig = json_encode($this->plugins_config, JSON_PRETTY_PRINT);

		$w = new FileWriter();
		$w->setDirPath(getPath('plugins'));
		$w->setData($newCompiledConfig);
		$w->setFilename('compiled.plugins.config');
		$w->setFileextension('json');
		$w->write();
	}

	protected function compilePluginCss(){
		$plugins_admin_css = '';
		if(count($this->plugins_admin_css)){
			$plugins_admin_css = implode("\n", $this->plugins_admin_css);
		}
		$w = new FileWriter();
		$w->setDirPath(getPath('resources/css'));
		$w->setData($plugins_admin_css);
		$w->setFilename('admin.app');
		$w->setFileextension('scss');
		$w->write();

		
		$plugins_client_css = '';
		if(count($this->plugins_client_css)){
			$plugins_client_css = implode("\n", $this->plugins_client_css);
		}
		$w = new FileWriter();
		$w->setDirPath(getPath('resources/css'));
		$w->setData($plugins_client_css);
		$w->setFilename('client.app');
		$w->setFileextension('scss');
		$w->write();
	}

	protected function saveCollectedEvents(){
		$cacheKey = \Plugins\Opoink\Liv\Lib\Event::CACHE_KEY;

		Cache::forget($cacheKey);

		foreach ($this->plugin_events as &$event) {
			usort($event,function($a, $b) {
				return $a['sort_order'] - $b['sort_order'];
			});
		}

		Cache::forever($cacheKey, $this->plugin_events);
	}
}
?>