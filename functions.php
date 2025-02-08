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
	function getAdminUrl(){
		return config('oliv.vite_admin_url');
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