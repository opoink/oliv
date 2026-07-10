<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


if(file_exists(base_path().'/routes/plugin_console.php')) {
	try {
		require_once(base_path().'/routes/plugin_console.php');
	} catch (\Exception $e) {
		/** do nothing for now */
	}
}