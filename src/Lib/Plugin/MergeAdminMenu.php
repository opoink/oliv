<?php
namespace Opoink\Oliv\Lib\Plugin;

class MergeAdminMenu {


	public function mergeMenus(array $base, array $overrides): array {
		foreach ($overrides as $overrideItem) {
			$nameToFind = $overrideItem['name'];
			$found = false;

			foreach ($base as $baseKey => &$baseItem) {
				// Named keys (e.g., 'settings' => [...])
				if (is_string($baseKey) && is_array($baseItem) && isset($baseItem['name']) && $baseItem['name'] === $nameToFind) {
					$baseItem = $this->mergeItem($baseItem, $overrideItem);
					$found = true;
					break;
				}

				// Indexed arrays
				if (is_array($baseItem) && isset($baseItem['name']) && $baseItem['name'] === $nameToFind) {
					$base[$baseKey] = $this->mergeItem($baseItem, $overrideItem);
					$found = true;
					break;
				}
			}

			// 🔥 If not found, add it as new item
			if (!$found) {
				$base[] = $overrideItem;
			}
		}

		return $base;
	}

	public function mergeItem(array $baseItem, array $overrideItem): array {
		// Merge links if present
		if (isset($overrideItem['child']) && isset($baseItem['children'])) {
			foreach ($overrideItem['child'] as $childIndex => $childItems) {
				if (!isset($baseItem['children'][$childIndex])) continue;

				foreach ($childItems as $subIndex => $subChild) {
					foreach ($baseItem['children'][$childIndex] as $key => &$existingChild) {
						if ($existingChild['name'] === $subChild['name']) {
							if (isset($subChild['links'])) {
								// Handle link override or removal
								$existingChild['links'] = $this->overrideLinks($existingChild['links'], $subChild['links']);
							}
						}
					}
				}
			}
		}

		// Simple key override (e.g. update route/label if provided)
		foreach ($overrideItem as $key => $val) {
			if ($key !== 'child') {
				$baseItem[$key] = $val;
			}
		}

		return $baseItem;
	}

	public function overrideLinks(array $originalLinks, array $overrideLinks): array {
		foreach ($overrideLinks as $override) {
			$found = false;
			foreach ($originalLinks as $i => $link) {
				if ($link['name'] === $override['name']) {
					$found = true;
					if (!empty($override['remove'])) {
						unset($originalLinks[$i]);
					} else {
						$originalLinks[$i] = array_merge($link, $override);
					}
					break;
				}
			}
			if (!$found && empty($override['remove'])) {
				$originalLinks[] = $override;
			}
		}
		// Reindex array
		return array_values($originalLinks);
	}

}
?>