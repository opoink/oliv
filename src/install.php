<?php
	define('ROOT', dirname(dirname(dirname(dirname(__DIR__)))));
	define('DS', DIRECTORY_SEPARATOR);

	require(ROOT.DS.'vendor'.DS.'autoload.php');

	$dirManager = new \Opoink\Oliv\Lib\Dirmanager();

	/** copy the plugins dir */
	$target = ROOT.DS.'vendor'.DS.'opoink'.DS.'oliv'.DS.'src'.DS.'resources'.DS.'plugins';
	$dirManager->copyDir($target, ROOT.DS.'plugin');

	/** copy the routes dir */
	$target = ROOT.DS.'vendor'.DS.'opoink'.DS.'oliv'.DS.'src'.DS.'resources'.DS.'routes';
	$dirManager->copyDir($target, ROOT.DS.'routes');

	/** copy the config dir */
	$target = ROOT.DS.'vendor'.DS.'opoink'.DS.'oliv'.DS.'src'.DS.'resources'.DS.'config';
	$dirManager->copyDir($target, ROOT.DS.'config');

	var_dump(ROOT);
?>