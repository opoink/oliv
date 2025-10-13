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
	function mergeByName(array $base, array $append): array
	{
		// Build associative lookup by "name"
		$map = [];

		foreach ($base as $item) {
			if (isset($item['name'])) {
				$map[$item['name']] = $item;
			} else {
				// unnamed entries get appended with numeric keys
				$map[] = $item;
			}
		}

		foreach ($append as $item) {
			$name = $item['name'] ?? null;

			if ($name && isset($map[$name])) {
				$merged = $map[$name];

				foreach ($item as $key => $value) {
					if ($key === 'children' && is_array($value)) {
						$baseChildren = $merged['children'] ?? [];
						$merged['children'] = $this->mergeChildrenRecursively($baseChildren, $value);
					} else {
						// override non-array or scalar values
						$merged[$key] = $value;
					}
				}

				$map[$name] = $merged;
			} else {
				// new name entirely
				if ($name) {
					$map[$name] = $item;
				} else {
					$map[] = $item;
				}
			}
		}

		// preserve consistent output structure
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
		// Normalize (unwrap [[...]])
		$normalize = function (array $children): array {
			$normalized = [];
			foreach ($children as $child) {
				if (is_array($child)) {
					$first = reset($child);
					$normalized[] = is_array($first) ? $first : $child;
				}
			}
			return $normalized;
		};

		$baseNormalized   = $normalize($baseChildren);
		$appendNormalized = $normalize($appendChildren);

		// Recursively merge the flattened children
		$merged = $this->mergeByName($baseNormalized, $appendNormalized);

		// Rewrap to restore the original [[ ... ]] format
		return array_map(fn($child) => [$child], $merged);
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