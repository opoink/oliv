<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


if(file_exists(base_path().'/routes/plugin_api.php')) {
	try {
		require_once(base_path().'/routes/plugin_api.php');
	} catch (\Exception $e) {
		/** do nothing for now */
	}
}
