<?php

use Illuminate\Support\Facades\Route;
use Plugins\Opoink\Cms\Http\Controllers\Admin\BlockController;
use Plugins\Opoink\Cms\Http\Controllers\Admin\PagesController;

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
		Route::group(['prefix' => 'cms'], function () use ($router) {
			Route::group(['prefix' => 'block'], function () use ($router) {
				Route::get('/', BlockController::class)->name('admin.cms.block.index');
				Route::get('add', [BlockController::class, 'addEdit'])->name('admin.cms.block.add');
				Route::get('edit/{id}', [BlockController::class, 'addEdit'])->name('admin.cms.block.edit');
				Route::post('save', [BlockController::class, 'saveAction'])->name('admin.cms.block.saveaction');
				Route::delete('delete/{id}', [BlockController::class, 'deleteAction'])->name('admin.cms.block.deleteaction');
			});

			Route::group(['prefix' => 'pages'], function () use ($router) {
				Route::get('/', PagesController::class)->name('admin.cms.pages.index');
				Route::get('add', [PagesController::class, 'addEdit'])->name('admin.cms.pages.add');
				Route::get('edit/{id}', [PagesController::class, 'addEdit'])->name('admin.cms.pages.edit');
				Route::post('save', [PagesController::class, 'saveAction'])->name('admin.cms.pages.saveaction');
			});
		});
	});
});
?>