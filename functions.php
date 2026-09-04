<?php

define('ROOT', dirname(dirname(dirname(__DIR__))));
define('DS', DIRECTORY_SEPARATOR);

use \Plugins\Opoink\Liv\Models\AdminUserRolesResource;
use \Plugins\Opoink\Liv\Lib\Inertia;


if (!function_exists('getPath')) {
	function getPath($path){
		$path = str_replace('/', DS, $path);
		$path = ltrim($path, DS);
		return ROOT . DS . $path;
	}
}


/**
 * return the admin route value
 */
if (!function_exists('getAdminUrl')) {
	function getAdminUrl(?string $path = null, ?array $params = null){
		if($path){
			$url = '/' . config('oliv.vite_admin_url') . $path;
			if(is_array($params)){
				$url .= '?' . http_build_query($params);
			}
			return $url;
		}
		else {
			return config('oliv.vite_admin_url');
		}
	}
}

/**
 * check if the current route is for admin area
 */
if (!function_exists('isAdminRoute')) {
	function isAdminRoute(){
		$path = explode('/', request()->path());

		if(isset($path[0]) && $path[0] == getAdminUrl()){
			return true;
		}
		else {
			return false;
		}
	}
}

/**
 * get roles of current loged in admin user
 */
if (!function_exists('getRolesResource')) {
	function getRolesResource($adminId){
		if (!session()->has('roles_resource')) {
			$resources =  AdminUserRolesResource::where('admin_user_role_id', $adminId)->get();
			$roles_resource = [];
			foreach ($resources as $resource) {
				$roles_resource[$resource->resource] = $resource->resource;
			}
			session()->put('roles_resource', $roles_resource);
			session()->save();
		}
		else {
			$roles_resource = session()->get('roles_resource');
		}
		return $roles_resource;
	}
}

/**
 * get authenticated admin user
 */
if(!function_exists('getAuthAdminUser')){
	function getAuthAdminUser(){
		$adminUser = auth()->guard('admin')->user();
		return $adminUser;
	}
}

/**
 * this function assumes that the user was walready signed in 
 * in admin area
 */
if (!function_exists('isRoleAllowed')) {
	function isRoleAllowed($resource){
		$adminUser = getAuthAdminUser();

		$rolesResource = getRolesResource($adminUser['admin_user_role_id']);

		if($adminUser['admin_type'] == 'super_admin'){
			/**
			 * always return true for super_admin
			 */
			return true;
		}
		else {
			/**
			 * if the $resource is an array, then all value should be in the 
			 * admin user role, if 1 value does not exist
			 * simply mean that the user is not allowed to take action
			 */
			if(is_array($resource)){
				$isAllowed = true;
				foreach ($resource as $key => $value) {
					if(!isset($rolesResource[$resource])){
						$isAllowed = false;
						break;
					}
				}
				return $isAllowed;
			}
			else {
				return isset($rolesResource[$resource]);
			}
		}
	}
}

$pluginsConfig = null;
if (!function_exists('getPluginsConfig')) {
	function getPluginsConfig(){
		global $pluginsConfig;

		if($pluginsConfig){
			return $pluginsConfig;
		}

		$target = getPath('plugins/config.json');

		$plugins = [
			'plugins' => []
		];
		if(file_exists($target)){
			$plugins = json_decode(file_get_contents($target), true);
		}

		$pluginsConfig = new \Opoink\Oliv\Lib\DataObject($plugins);
		return $pluginsConfig;
	}
}

if (!function_exists('getPluginDir')) {
	function getPluginDir($plugin){
		return getPath('plugins/'.str_replace('_', DS, $plugin));
	}
}

if (!function_exists('inertiaRender')) {
	function inertiaRender(string $component, array|\Illuminate\Contracts\Support\Arrayable $props = []){
		$props = $props instanceof \Illuminate\Contracts\Support\Arrayable ? $props->toArray() : $props;

		if(!array_key_exists('page_assets', $props)){
			$pageAssets = null;
			$hotFile = public_path('hot');
			$manifestPath = public_path('build/manifest.json');

			if(!file_exists($hotFile) && is_readable($manifestPath)){
				static $cachedManifest = null;
				static $cachedManifestPath = null;
				static $cachedManifestModifiedAt = null;

				$manifestModifiedAt = filemtime($manifestPath);
				if(
					$cachedManifestPath !== $manifestPath ||
					$cachedManifestModifiedAt !== $manifestModifiedAt
				){
					$manifestContents = file_get_contents($manifestPath);
					$decodedManifest = is_string($manifestContents)
						? json_decode($manifestContents, true)
						: null;

					$cachedManifest = is_array($decodedManifest) ? $decodedManifest : null;
					$cachedManifestPath = $manifestPath;
					$cachedManifestModifiedAt = $manifestModifiedAt;
				}

				if(is_array($cachedManifest)){
					$normalizedComponent = str_replace('\\', '/', $component);
					$normalizedComponent = ltrim($normalizedComponent, '/');
					$normalizedComponent = preg_replace('/\.vue$/i', '', $normalizedComponent);
					$entryCandidates = [
						'plugins/' . $normalizedComponent . '.vue',
						'resources/js/Pages/' . $normalizedComponent . '.vue',
						'storage/framework/vue/pages/' . $normalizedComponent . '.vue',
					];

					$entry = null;
					foreach($entryCandidates as $entryCandidate){
						if(array_key_exists($entryCandidate, $cachedManifest)){
							$entry = $entryCandidate;
							break;
						}
					}

					if($entry){
						$visited = [];
						$cssFiles = [];
						$collectCss = function($manifestEntry) use (&$collectCss, &$visited, &$cssFiles, $cachedManifest){
							if(isset($visited[$manifestEntry]) || !isset($cachedManifest[$manifestEntry])){
								return;
							}

							$visited[$manifestEntry] = true;
							$chunk = $cachedManifest[$manifestEntry];
							foreach(($chunk['imports'] ?? []) as $import){
								$collectCss($import);
							}

							foreach(($chunk['css'] ?? []) as $cssFile){
								if(is_string($cssFile)){
									$cssFiles[$cssFile] = true;
								}
							}
						};

						$collectCss($entry);
						$pageAssets = [
							'css' => array_map(
								fn ($file) => '/build/' . ltrim($file, '/'),
								array_keys($cssFiles)
							)
						];
					}
				}
			}

			$props['page_assets'] = $pageAssets;
		}

		return Inertia::render($component, $props);
	}
}

if (!function_exists('b64UrlEncode')) {
	function b64UrlEncode($data){
		return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
	}
}

if (!function_exists('b64UrlDecode')) {
	function b64UrlDecode($data) {
		if ($remainder = strlen($data) % 4) {
			$data .= str_repeat('=', 4 - $remainder);
		}
		return base64_decode(strtr($data, '-_', '+/'));
	}
}

if (!function_exists('getGlobalComponentName')) {
	function getGlobalComponentName($path) {
		$path = str_replace('/', DS, $path);
		$path = str_replace(ROOT.DS.'plugins'.DS, '', $path);
		$path = str_replace('resources'.DS.'js'.DS, '', $path);
		$path = str_replace(DS, ' ', $path);
		$path = ucwords($path);
		$path = explode('.', $path);
		unset($path[count($path) - 1]);
		$path = implode('', $path);
		$path = str_replace(' ', '', $path);
		return $path;
	}
}


?>
