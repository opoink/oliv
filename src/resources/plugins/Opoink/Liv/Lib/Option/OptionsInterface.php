<?php
namespace Plugins\Opoink\Liv\Lib\Option;

interface OptionsInterface {

	public function toOptionArray();

	public function getOptions();

	public function getLabel(string $key);
}

?>