<?php
namespace Plugins\Opoink\Liv\Lib;

class Inertia extends \Inertia\Inertia {

	public static function render(string $component, array|\Illuminate\Contracts\Support\Arrayable $props = []){
		/**
		 * TODO: replace the $component value if theme file exist
		 */
		return parent::render($component, $props);
	}
}
?>