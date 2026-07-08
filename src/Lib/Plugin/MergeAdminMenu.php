<?php
namespace Opoink\Oliv\Lib\Plugin;

class MergeAdminMenu {

	/**
	 * Recursively merge configuration arrays using the "name" key as reference.
	 * - Scalars in $append override $base.
	 * - "children" arrays are merged recursively.
	 * - Unnamed elements are appended as-is.
	 *
	 * @param array $base
	 * @param array $append
	 * @return array
	 */
	public function mergeByName(array $base, array $append): array
	{
		// Build associative lookup by "name"
		$map = [];

		foreach ($base as $item) {
			/**
			 * Required: All items must have a `name` attribute.
			 */
			if (isset($item['name'])) {
				$map[$item['name']] = $item;
			}
		}

		foreach ($append as $item) {
			$name = $item['name'] ?? null;
			if(!$name){
				continue;
			}

			if(!isset($map[$name])){
				$map[$name] = $item;
			}
			else {
				foreach($item as $key1 => $val1) {
					if($key1 == 'name'){
						continue;
					}

					if($key1 == 'children') {
						$map[$name][$key1] = $this->mergeChildrenRecursively($map[$name][$key1], $item[$key1]);
					}
					elseif($key1 == 'links') {
						$map[$name][$key1] = $this->mergeByName($map[$name][$key1], $item[$key1]);
					}
					else {
						$map[$name][$key1] = $item[$key1];
					}
				}
				// dump($item, $map[$name]);
			}
		}

		return array_values($map);
	}

	/**
	 * Merges "children" arrays recursively.
	 * Each level of children is wrapped as [[ ... ]], so we normalize/unpack before merging.
	 *
	 * @param array $baseChildren
	 * @param array $appendChildren
	 * @return array
	 */
	public function mergeChildrenRecursively(array $baseChildren, array $appendChildren): array
	{	
		$normalize = function(array $data){
			$map0 = [];
			foreach ($data as $key1 => $value1) {
				$map1 = [];
				foreach ($data[$key1] as $key2 => $value2) {
					if (isset($value2['name'])) {
						$map1[$value2['name']] = $value2;
					}
				}

				$map0[] = $map1;
			}
			return $map0;
		};

		$baseNormalized   = $normalize($baseChildren);
		$appendNormalized = $normalize($appendChildren);

		foreach ($baseNormalized as $key => $value) {
			$baseNormalized[$key] = $this->mergeByName($baseNormalized[$key], $appendNormalized[$key]);
		}

		return $baseNormalized;
	}

	public function removeMarkedEntries(array $array): array {
		$result = [];

		foreach ($array as $key => $item) {
			// If it's not an array, keep as-is
			if (!is_array($item)) {
				$result[$key] = $item;
				continue;
			}

			// Skip this item entirely if 'remove' is true
			if (isset($item['remove']) && $item['remove'] === true) {
				continue;
			}

			// Recursively check 'children'
			if (isset($item['children']) && is_array($item['children'])) {
				$item['children'] = array_map([$this, 'removeMarkedEntries'], $item['children']);
			}

			// Recursively check 'links'
			if (isset($item['links']) && is_array($item['links'])) {
				$item['links'] = $this->removeMarkedEntries($item['links']);
			}

			$result[$key] = $item;
		}

		return array_values($result); // Reset numeric keys
	}

}
?>