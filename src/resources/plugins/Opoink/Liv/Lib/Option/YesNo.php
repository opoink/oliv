<?php
namespace Plugins\Opoink\Liv\Lib\Option;

class YesNo {

	const YES = "yes";
	const NO = "no";

	public function toOptionArray(){
		return [
			["label" => self::YES, "value" => self::YES],
			["label" => self::NO, "value" => self::NO]
		];
	}
}
?>