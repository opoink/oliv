<?php

use Illuminate\Support\Facades\Route;
use Plugins\Opoink\Email\Http\Controllers\Admin\EmailController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['adminauth'])->group(function () use ($router) {
	Route::group(['prefix' => getAdminUrl()], function () use ($router) {
		Route::group(['prefix' => 'emails'], function () use ($router) {
			Route::get('/', EmailController::class);
			Route::get('add', [EmailController::class, 'addEdit'])->name('admin.emails.add');
			Route::get('edit/{id}', [EmailController::class, 'addEdit']);
			Route::post('save', [EmailController::class, 'saveAction']);
			Route::delete('delete/{id}', [EmailController::class, 'deleteAction'])->name('admin.emails.deleteaction');
		});
	});
});
?>