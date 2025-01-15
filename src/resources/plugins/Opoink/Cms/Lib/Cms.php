<?php
/**
* Copyright 2018 Opoink Framework (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*/
namespace Plugins\Opoink\Cms\Lib;

class Cms {

	public function __construct(
		protected \Opoink\Oliv\Lib\Writer $writer
	) {}

	/**
	 * @param $identifier string 
	 * return string
	 */
	public function getDirName($identifier){
		$dirName = ucwords(str_replace('-', ' ', $identifier));
		$dirName = str_replace(' ', '', $dirName);
		return $dirName;
	}

	/**
	 * get the component path
	 * @param $dirName string 
	 * @param $type string Blocks or Pages
	 * return string
	 */
	public function getComponentPath($dirName, $type="Blocks"){
		return ROOT.DS.'resources'.DS.'js'.DS.'Cms'.DS.$type.DS.$dirName;
	}

	public function saveComponent($identifier, $content, $type="Blocks"){
		$dirName = $this->getDirName($identifier);
		$path = $this->getComponentPath($dirName, $type);
		$this->writer->setDirPath($path);

		$tplContent = '';
		$dataContent = "";

		if($content['editor'] == 'gjs'){
			$pattern = '#<script(?:[^>"]*(?:"[^"]*")?)*>((?:"(?:[^\\\\\\n"]*(?:\\\\.)*)*"|\'(?:[^\\\\\\n\']*(?:\\\\.)*)*\'|<[^/]?|/\\*(?:[^*]|\\*[^/]?)*\\*/|//.*|/[^/*]|[^\'"</])*)</script>#';

			$dataContent .= "<script setup>\n";
			$dataContent .= "\timport './VueComponent.scss';\n";
			$dataContent .= "\timport { onMounted } from 'vue';\n";
			$dataContent .= "\tonMounted(() => {" . PHP_EOL;
				preg_match($pattern, $content['content']['html'], $matches);
				if(isset($matches[1])){
					$dataContent .= "\t\t" . $matches[1] . PHP_EOL;
				}
			$dataContent .= "\t})" . PHP_EOL;
			$dataContent .= "</script>\n";

			$tplContent = preg_replace($pattern, '', $content['content']['html']);
			
			$this->writer->setData('#'.$dirName.'{'.$content['content']['css'].'}');
			$this->writer->setFilename('VueComponent');
			$this->writer->setFileextension('scss');
			$this->writer->write();
		}
		elseif($content['editor'] == 'tinymce'){
			$tplContent = $content['content'];
		}

		
		$dataContent .= "<template>\n";
		$dataContent .= "\t<div id=\"".$dirName."\">\n";
		$dataContent .= "\t\t" . $tplContent . PHP_EOL;
		$dataContent .= "\t</div>\n";
		$dataContent .= "</template>";

		$this->writer->setData($dataContent);
		$this->writer->setFilename('VueComponent');
		$this->writer->setFileextension('vue');
		$this->writer->write();
	}
}
?>