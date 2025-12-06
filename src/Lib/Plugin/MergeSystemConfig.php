<?php
namespace Opoink\Oliv\Lib\Plugin;


use Opoink\Oliv\Lib\Writer as FileWriter;

class MergeSystemConfig {
	
	/**
	 * @var array the collected plugin system config
	 */
	protected $addedSystemConfig = []; 
	
	protected $mergedSystemConfig = [];

	protected $noSortOrder = 99999;

	public function addSystemConfig(array $systemConfig){
		$this->addedSystemConfig[] = $systemConfig;
	}

	public function merge(){
		foreach ($this->addedSystemConfig as $pluginSystemConfig) {
			$this->mergedSystemConfig = $this->mergeConfigTrees($this->mergedSystemConfig, $pluginSystemConfig);
		}

		$this->mergedSystemConfig = $this->sortOrder($this->mergedSystemConfig);
		$this->saveAdminMenu();
	}

	protected function sortOrder(array $data){
		$toSortData = [];

		foreach ($data as $key => $value) {
			$_keyIndex = 0;
			if(isset($value['sort_order'])){
				$_keyIndex = (int)$value['sort_order'];
			}
			else {
				$_keyIndex = $this->noSortOrder;
				$value['sort_order'] = $this->noSortOrder;
				$this->noSortOrder += 10;
			}

			if(isset($value['children'])){
				$value['children'] = $this->sortOrder($value['children']);
			}
			
			if(isset($toSortData[$_keyIndex])){
				while (isset($toSortData[$_keyIndex])) {
					$_keyIndex++;
				}
				$_keyIndex = $_keyIndex;
				$value['sort_order'] = $_keyIndex;
			}

			$toSortData[$_keyIndex] = $value;
		}

		ksort($toSortData);
		$toSortData = array_values($toSortData);

		return $toSortData;
	}

	protected function mergeConfigTrees(array ...$trees): array {
		$merged = [];

		foreach ($trees as $tree) {
			foreach ($tree as $node) {
				if (!isset($node['name'])) {
					$merged[] = $node;
					continue;
				}

				$index = array_search($node['name'], array_column($merged, 'name'));

				// new top-level node
				if ($index === false) {
					$merged[] = $node;
					continue;
				}

				// merge into existing node
				$merged[$index] = $this->mergeNodes($merged[$index], $node);
			}
		}

		return $merged;
	}

	protected function mergeNodes(array $a, array $b): array {
		foreach ($b as $key => $value) {

			// Merge children recursively
			if ($key === 'children') {
				$a['children'] = $this->mergeChildren(
					$a['children'] ?? [],
					$b['children'] ?? []
				);
				continue;
			}

			// For scalar values or arrays → override or merge
			if (is_array($value) && isset($a[$key]) && is_array($a[$key])) {
				$a[$key] = array_replace_recursive($a[$key], $value);
			} else {
				$a[$key] = $value; // override or add new
			}
		}

		return $a;
	}

	protected function mergeChildren(array $aChildren, array $bChildren): array {
		// Normalize single child → array
		if (isset($aChildren['name'])) $aChildren = [$aChildren];
		if (isset($bChildren['name'])) $bChildren = [$bChildren];

		// Merge lists of nodes recursively
		$out = $this->mergeConfigTrees($aChildren, $bChildren);

		// Sort by sort_order if present
		// usort($out, function($x, $y) {
		// 	$sx = $x['sort_order'] ?? 99999;
		// 	$sy = $y['sort_order'] ?? 99999;
		// 	return $sx <=> $sy;
		// });

		return $out;
	}

	public function flattenConfig(array $nodes, string $prefix = ''): array {
		$result = [];

		foreach ($nodes as $node) {
			if (!isset($node['name'])) {
				continue;
			}

			// Build the key path: prefix.name
			$path = $prefix === ''
				? $node['name']
				: $prefix . '.' . $node['name'];

			// If this node is a field → store the value
			if (($node['type'] ?? null) === 'field') {
				$result[$path] = $node['value'] ?? null;
				continue;
			}

			// If node has children → recurse
			if (isset($node['children']) && is_array($node['children'])) {
				$result = array_merge(
					$result,
					$this->flattenConfig($node['children'], $path)
				);
			}
		}

		return $result;
	}

	protected function splitLevels(array $merged) {
		$firstSecond = [];
		$thirdAndAbove = [];

		foreach ($merged as $topNode) {

			$tab = $topNode;
			$tab["children"] = [];

			$second = isset($topNode['children']) ? $topNode['children'] : null;
			if($second){
				$section = $second;
				foreach ($section as $keySec => $valueSec) {
					$section[$keySec]['children'] = [];

					if(isset($valueSec['children']) && count($valueSec['children'])){
						$key = $topNode["name"] . "." . $valueSec["name"];
						$thirdAndAbove[$key] = $valueSec['children'];
					}
				}
				$tab["children"] = $section;
			}
			$firstSecond[] = $tab;
		}

		return [$firstSecond, $thirdAndAbove];
	}

	public function toValue(array $merged){
		$flatten = [];

		foreach ($merged as $key => $value) {
			if(!isset($value['type'])){
				throw new \Exception($value['name'] . ": must have a type either tab, section, group or field", 500);
			}

			if(!in_array($value['type'], ['tab', 'section', 'group', 'field'])){
				throw new \Exception($value['name'] . ": type " . $value['type'] . " is unknown", 500);
			}

			if($value['type'] == 'field'){
				$flatten[$value['name']] = $value['value'] ??  "";
			}
			else if(isset($value['children'])){
				$flatten[$value['name']] = $this->toValue($value['children']);
			}
		}
		
		return $flatten;
	}


	protected function saveAdminMenu(){
		$targetDir = storage_path(DS."app".DS."private".DS."plugins".DS."etc".DS."admin");

		$data = '<?php' . PHP_EOL;
		$data .= 'return ' . var_export($this->mergedSystemConfig, true) . PHP_EOL;
		$data .= '?>';
		
		$w = new FileWriter();
		$w->setDirPath($targetDir);
		$w->setData($data);
		$w->setFilename('system');
		$w->setFileextension('php');
		$w->write();


		/**
		 * Flatten the arreay to get value easier
		 */
		$flatttenConfig = $this->toValue($this->mergedSystemConfig);

		$data = '<?php' . PHP_EOL;
		$data .= 'return ' . var_export($flatttenConfig, true) . PHP_EOL;
		$data .= '?>';

		$w = new FileWriter();
		$w->setDirPath($targetDir);
		$w->setData($data);
		$w->setFilename('system_value');
		$w->setFileextension('php');
		$w->write();


		/**
		 * separate the first and second level for tab and section
		 * third level and about for group
		 * this will make easier for the UI to load the system config
		 */
		list($firstSecond, $thirdAndAbove) = $this->splitLevels($this->mergedSystemConfig);
		$data = '<?php' . PHP_EOL;
		$data .= 'return ' . var_export($firstSecond, true) . PHP_EOL;
		$data .= '?>';

		$w = new FileWriter();
		$w->setDirPath($targetDir);
		$w->setData($data);
		$w->setFilename('system_tab_section');
		$w->setFileextension('php');
		$w->write();

		foreach ($thirdAndAbove as $key => $value) {
			$data = '<?php' . PHP_EOL;
			$data .= 'return ' . var_export($value, true) . PHP_EOL;
			$data .= '?>';

			$w = new FileWriter();
			$w->setDirPath($targetDir.DS."system_tab_section");
			$w->setData($data);
			$w->setFilename($key);
			$w->setFileextension('php');
			$w->write();
		}
	}
}
?>