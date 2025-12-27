<?php

use Illuminate\Support\Facades\Route;
use Plugins\Opoink\Liv\Http\Controllers\Admin\Users\Admins;
use Plugins\Opoink\Liv\Http\Controllers\Admin\Roles\AdminsRoles;
use Plugins\Opoink\Liv\Http\Controllers\Admin\Login;

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
if(config('oliv.vite_oliv_welcome_page')){
	Route::get('/', \Plugins\Opoink\Liv\Http\Controllers\Client\Index::class)->name('client.index');
}

Route::middleware(['adminauth'])->group(function () use ($router) {
	Route::group(['prefix' => getAdminUrl()], function () use ($router) {
		Route::get('/', \Plugins\Opoink\Liv\Http\Controllers\Admin\Index::class)->name('admin.index');
		
		Route::get('/logout', [Login::class, 'adminLogout'])->name('admin.logout');

		Route::group(['prefix' => 'users'], function () use ($router) {
			Route::get('/admins', Admins::class)->name('admin.users.admins.index');
			Route::get('/admins/add', [Admins::class, 'addEdit'])->name('admin.users.admins.add');
			Route::get('/admins/edit/{id}', [Admins::class, 'addEdit'])->name('admin.users.admins.edit');
			Route::post('/admins/save', [Admins::class, 'saveAction'])->name('admin.users.admins.saveaction');
			Route::delete('/admins/delete/{id}', [Admins::class, 'deleteAction'])->name('admin.users.admins.delete');

			
			Route::get('/admins/roles', AdminsRoles::class)->name('admin.users.admins.roles.index');
			Route::get('/admins/roles/add', [AdminsRoles::class, 'addEdit'])->name('admin.users.admins.roles.add');
			Route::get('/admins/roles/edit/{id}', [AdminsRoles::class, 'addEdit'])->name('admin.users.admins.roles.edit');
			Route::post('/admins/roles/save', [AdminsRoles::class, 'saveAction'])->name('admin.users.admins.roles.saveaction');
			Route::delete('/admins/roles/delete/{id}', [AdminsRoles::class, 'deleteAction'])->name('admin.users.admins.roles.delete');
		});

		Route::group(['prefix' => 'settings'], function () use ($router) {
			Route::get('/', \Plugins\Opoink\Liv\Http\Controllers\Admin\Settings\Settings::class)->name('admin.settings.index');
			Route::post('/save', [\Plugins\Opoink\Liv\Http\Controllers\Admin\Settings\Settings::class, 'saveSettings'])->name('admin.settings.index');
		});

		
		Route::post('/adminlisting/bookmark/save/visible/columns', [\Plugins\Opoink\Liv\Http\Controllers\Admin\AdminListing\Bookmark::class, 'saveVisibleColumns']);
	});
});

/**
 * Admin login routes
 */
Route::group(['prefix' => getAdminUrl()], function () use ($router) {
	Route::get('/login', Login::class)->name('admin.login');
	Route::post('/login', [Login::class, 'authUser'])->name('admin.login.action');

	Route::get('/forgot-password', [Login::class, 'forgotPassword'])->name('admin.forgot-password');
	Route::post('/forgot-password/send/code', [Login::class, 'forgotPasswordCode'])->name('admin.forgot-password.send.code');
	Route::get('/reset-password', [Login::class, 'resetPassword'])->name('admin.reset-password');
	Route::post('/reset-password/save', [Login::class, 'resetPasswordSave'])->name('admin.reset-password.actionsave');
});
?>