<?php
	define('ROOT', dirname(dirname(dirname(dirname(__DIR__)))));
	define('DS', DIRECTORY_SEPARATOR);

	require(ROOT.DS.'vendor'.DS.'autoload.php');

	$dirManager = new \Opoink\Oliv\Lib\Dirmanager();

	$plguinTarget = ROOT.DS.'vendor'.DS.'opoink'.DS.'oliv'.DS.'src'.DS.'resources'.DS.'plugins';

	$dirManager->copyDir($plguinTarget, ROOT.DS.'plugin');
	var_dump(ROOT);
?>