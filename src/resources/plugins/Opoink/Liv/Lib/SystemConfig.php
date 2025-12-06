<?php
namespace Plugins\Opoink\Liv\Lib;

use Plugins\Opoink\Liv\Models\SystemConfig AS SystemConfigModel;

class SystemConfig extends \Opoink\Oliv\Lib\DataObject {

	protected $isLoaded = false;

	protected $loadedValue = [];

	public function __construct(array $data = []){
		$this->loadConfig();
	}
	
	public function loadConfig(){
		if($this->isLoaded){
			return false;
		}
		
		$targetFile = storage_path("app/private/plugins/etc/admin/system_value.php");
		if(file_exists($targetFile) && is_file($targetFile)){
			$this->setData(include($targetFile));
		}
	}

	/**
	 * @return \Plugins\Opoink\Liv\Lib\SystemConfig
	 */
	public function resetLoadedValue(){
		$this->loadedValue = [];
		return $this;
	}

	/**
	 * @return ?string
	 */
	public function getValue(string $key){
		$key = trim($key, "/");

		$value = null;
		if(!isset($this->loadedValue[$key])){
			$isSystemValue = false;
			$systemConfigModel = SystemConfigModel::where('path', $key)->first();
			if(!$systemConfigModel){
				$value = $this->getData($key);
				$isSystemValue = true;
			}
			else {
				$value = $systemConfigModel->value;
				$isSystemValue = false;
			}

			$this->loadedValue[$key] = [
				"value" => $value,
				"is_system_value" => $isSystemValue,
			];
		}

		return $this->loadedValue[$key]["value"];
	}

	/**
	 * @return bool
	 */
	public function isSystemValue(string $key){
		if(!isset( $this->loadedValue[$key])){
			$this->getValue($key);
		}
		return $this->loadedValue[$key]['is_system_value'];
	}

	public function assignValue(array $data, string $prefix=""){
		foreach ($data as $key => $value) {
			if($data[$key]['type'] == "field"){
				$path = $prefix . "/" . $value['name'];
				if($data[$key]['field']['type'] == 'multiselect'){
					$v = $this->getValue($path);
					if($v){
						$data[$key]['_value'] = explode(",", $v);
					}
					else {
						$data[$key]['_value'] = [];
					}	
				}
				else {
					$data[$key]['_value'] = $this->getValue($path);
				}

				$data[$key]['_is_system_value'] = $this->isSystemValue($path);
			}
			elseif(isset($value['children'])) {
				$data[$key]['children'] = $this->assignValue($data[$key]['children'], $prefix."/".$value['name']);
			}
		}

		return $data;
	}

	public function saveSystemConfig(array $data){
		foreach ($data as $path => $value) {
			if($value['is_system_value']){
				$this->deleteByPath($path);
			}
			else {
				$systemConfigModel = SystemConfigModel::where('path', $path)->first();
				if(!$systemConfigModel){
					$systemConfigModel = new SystemConfigModel([
						'path' => $path,
						'value' => $value['value']
					]);

					$systemConfigModel->save();
				}
				else {
					$systemConfigModel->value = $value['value'];
					$systemConfigModel->save();
				}
			}
			
		}
	}

	public function deleteByPath(string $path){
		$systemConfigModel = SystemConfigModel::where('path', $path)->first();
		if($systemConfigModel){
			$systemConfigModel->delete();
		}
	}

}
?>