<?php
namespace Plugins\Opoink\Liv\Lib\Option;

class Options implements OptionsInterface {

	public function toOptionArray(){
		$options = $this->getOptions();
		$opts = [];
		foreach ($options as $key => $value) {
			$opts[] = ["label" => ucwords(str_replace("_", " ", $value)), "value" => $value];
		}
		return $opts;
	}

	public function getOptions(){
		return [];
	}

	public function getLabel(string $key){
		$options = $this->getOptions();

		if(isset($options[$key])){
			return ucwords(str_replace("_", " ", $options[$key]));
		}
		else {
			throw new \Exception("Unknown key " . $key, 500);
		}
	}
}
?>