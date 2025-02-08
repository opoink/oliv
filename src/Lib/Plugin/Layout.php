<?php
/**
* Copyright 2025 oliv (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Opoink\Oliv\Lib\Plugin;

use Opoink\Oliv\Lib\Writer as FileWriter;

class Layout {

	public function createLayoutByPageName($pageName){
		$targetDir = ROOT.DS.'storage'.DS.'framework'.DS.'vue'.DS.'pages';
		$fileName = str_replace(' ', '', ucwords(str_replace('.', ' ', $pageName)));
		$ext = 'vue';

		$targetFile = $targetDir.DS.$fileName.'.'.$ext;

		if(!file_exists($targetFile)){
			$this->createPageComponent($pageName, $targetDir, $fileName, $ext);
		}
	}

	public function createPageComponent($pageName, $targetDir, $fileName, $ext){
		$plugins = getPluginsConfig()->getData('plugins');

		$mergedLayoutData = [];
		foreach($plugins as $plugin){
			$layoutData = ROOT.DS.'plugins'.DS.str_replace('_', DS, $plugin).DS.'resources'.DS.'layout'.DS.$pageName.'.json';
			if(file_exists($layoutData)){
				$layoutData = file_get_contents($layoutData);
				$layoutData = json_decode($layoutData, true);

				$mergedLayoutData = array_merge_recursive($mergedLayoutData, $layoutData);
			}
		}

		$content = $this->toVueComponent($mergedLayoutData);


		$w = new FileWriter();
			
		$w->setDirPath($targetDir);
		$w->setData($content);
		$w->setFilename($fileName);
		$w->setFileextension($ext);
		$w->write();
	}

	private function toVueComponent($mergedLayoutData, &$imports=[], $level=0){
		$tab = str_repeat("\t", $level);
		$contentString = '' . PHP_EOL;


		foreach ($mergedLayoutData as $nodeName => $data) {
			if(isset($data['remove'])){
				continue;
			}

			$imports[] = $data['import'];

			$attr = [];
			if(isset($data['attr'])){
				foreach ($data['attr'] as $attrKey => $attrValue) {
					$attr[] = $attrKey.'="'.$attrValue.'"';
				}
			}

			$_attr = "";
			if(count($attr)){
				$_attr = " " . implode(' ', $attr);
			}

			$contentString .= $tab . '<'.$nodeName.$_attr.'>' . PHP_EOL;
			if(isset($data['children'])){
				$contentString .= $this->toVueComponent($data['children'], $imports, $level + 1);
			}
			$contentString .= $tab . '</'.$nodeName.'>' . PHP_EOL;
		}

		$scriptString = '';
		if($level == 0){
			$scriptString .= '<script setup lang="ts">' . PHP_EOL;
			$scriptString .= "\t" . implode(PHP_EOL . "\t", $imports) . PHP_EOL;
			$scriptString .= '</script>' . PHP_EOL;
			$scriptString .= '<template>' . PHP_EOL;
		}

		$scriptString .= $contentString;

		if($level == 0){
			$scriptString .= '</template>' . PHP_EOL;
		}

		return $scriptString;
	}
}
?>