## Getting Started

## Installation
- Requirements
	- PHP 8.3 and up
	- Node v18.17.0, v23.5.0
	- MySQL: this package is created using MySQL version 9.1.0 - MySQL Community Server - GPL
- Install Laravel 11.31.x and above without a starter kit, see Laravel's [documentation](https://laravel.com/docs)
	- `Would you like to install a starter kit? [No starter kit]:`
	- `Which database will your application use? [mysql]`
	- `Default database updated. Would you like to run the default database migrations? (yes/no) [no]`
- Install [oliv](https://github.com/opoink/oliv) package `composer require opoink/oliv`
	- Let Laravel discover the Oliv package `php artisan package:discover` if not discovered yet
 	- run the command `php artisan oliv:install` this will initiate all required files 
- (optional) change the value of the `VITE_ADMIN_URL` of your choice
- (optional) Change the value of the `VITE_INERTIA_SSR_PORT` in `ecosystem.config.js` of your choice
- Update database host, user, pass, name
- create file `plugins/config.json` you can copy it from `plugins/config.sample.json`
- run the command `php artisan oliv:plugins-update` this will compile and update all plugins set at the config.json file
- Optional `php artisan migrate` to migrate database
- run command `npm install` or `npm i`

## Run laravel
- `php artisan serve`

## Run InertiaJS
- `npm run dev`

## Build InertiaJS App
- build only: `npm run build` 
- build with SSR: `npm run build:ssr`
- using php artisan: `php artisan inertia:start-ssr`
- using pm2: `pm2 start ecosystem.config.js`


## Commands
- `php artisan oliv:plugins-update`
- Please refer to Laravel documentation for the rest of the command

## Clear the View Cache 
- `php artisan view:clear`: run this command if the domain URL is changed, or a new route is added

## Global Components
In the `plugins/<VendorName\>/<PluginName>/resources/js/GlobalComponents` add your vue component file with ext of lowercase `.vue`

e.g `NewComponent.vue` then you can call it on your vue template as `<VendorNamePluginNameGlobalComponentsNewComponent />`

If you add a vue file into sub-directories e.g `plugins/<VendorName>/<PluginName>/resources/js/GlobalComponents/Blog/Post/Comments.vue` then you can call it in your template as `<VendorNamePluginNameGlobalComponentsBlogPostComments />`

All global components are ready and available in all Vue templates. 

Note: run `php artisan oliv:plugins-update` every time a new Global Component file is added or removed


## Event/Listeners
- On your plugin create a directory `EventListeners` e.g. `plugins/<VendorName>/<PluginName>/EventListeners`
- Inside `EventListeners` create a file `EventList.php`
	```php
	<?php
		return [
			[ 
				'name' => 'my_event_name', // event name
				'listener' => \Plugins\<VendorName\>\<PluginName>\EventListeners\MyEventListener::class, // event handler class
				'sort_order' => 10
			] 
		]
	?>
	```
- Create event listener class in `plugins/<VendorName>/<PluginName>/EventListeners/<MyEventListener.php>`
	```php
	<?php
		namespace Plugins\<VendorName>\<PluginName>\EventListeners;
		class MyEventListener {
			public function handle(\Opoink\Oliv\Lib\DataObject $data){
				...
				$myData = $data->getData('my_data');
				...
			}
		}
	?>
	```
 - Common event names
   - `db_<TableName>_save_before` This event is triggered only for specific database tables before the save.
   - `db_<TableName>_save_after` This event is triggered only for specific database tables after the save but not yet committed, therefore, when an event handler throws an exception it will rollback.
   - `db_<TableName>_save_commit_after` This event is triggered only for specific database tables after the save, therefore, when an event handler throws an exception it will not rollback.
   - `db_model_commit_after` This event is triggered on model save regardless of the table name.
   - `Plugins_Opoink_Liv_Lib_Facades_Event_Login_authUser` This event is dispatched during the login method, allowing you to override and customize the login process according to your specific requirements..
   - `Plugins_Opoink_Liv_Http_Middleware_AdminAuthenticated_handle_before` This event is triggered to check whether the user is logged in. The listener can override the default authentication check, allowing for custom login validation logic.
  
Note: We are not using the default Laravel event listeners and dispatchers; instead, we are using custom events.

## Theme
All VueJS components, SCSS styles, and images can be customized by creating a theme.
To create a theme, simply create a new directory at `theme/theme_name` from the root of your Laravel installation.

The `theme_name` can be configured via your `.env` file at `VITE_OLIV_THEME` the default theme name is `default`

To override a specific file, follow the same file path structure as the original plugin. For example, if you want to change the content of the login page, copy the file from
`root_rirectory/plugins/Opoink/Liv/resources/js/Pages/Admin/Users/Login.vue` to `root_rirectory/theme/default/Opoink/Liv/resources/js/Pages/Admin/Users/Login.vue`

## Admin auth
By default, OLIV comes with built-in admin authentication using Laravel's admin guard. However, if your application requires a different authentication method, you can override the default behavior.

To override the default authentication:
1. Create an event listener to intercept the login authentication process.
	```php
	<?php
		return [
			[ 
				'name' => 'Plugins_Opoink_Liv_Lib_Facades_Event_Login_authUser', // event name
				'listener' => \Plugins\<VendorName\>\<PluginName>\EventListeners\MyEventListener::class, // event handler class
				'sort_order' => 1
			] 
		]
	?>
	```
2. In your event listener, implement your custom logic for validating the user.
	```php
	<?php
		namespace Plugins\<VendorName>\<PluginName>\EventListeners;
		class MyEventListener {
			public function handle(\Opoink\Oliv\Lib\DataObject $data){
				$request = $data->getData('request');
			
			        $username = $request->input('email');
			        $password = $request->input('password');
			
			        $isValid = /** your validation login */;
			
			        if($isValid){
 					$myModelAuthenticatable = new \Plugins\<VendorName>\<PluginName>\Models\MyModelAuthenticatable()
			            	auth()->guard('admin')->login($myModelAuthenticatable);
			        }
			}
		}
	?>
	```
 3. If the primary key of your model is not `id`, you will need to update the VueJS components that reference `id` to use your model’s actual primary key.
 4. In your `.env` file, set the `OLIV_AUTH_ADMIN_USER_MODEL` variable: `OLIV_AUTH_ADMIN_USER_MODEL="Plugins_<VendorName>_<PluginName>_Models_MyModelAuthenticatable"` Note: the backslashes in the model class path should be replaced with underscores. For example: `Plugins\<VendorName>\<PluginName>\Models\MyModelAuthenticatable` should be written as `Plugins_<VendorName>_<PluginName>_Models_MyModelAuthenticatable`
 5. Add the following entry to your database `admin` table if it does not already exist.
    - admin_type
    - admin_user_role_id
    - forgot_password_code
    - forgot_password_code_expire
    - firstname
    - lastname
    - email
6. Finally, if your table already contains admin users, choose one and set its `admin_type` value to `super_admin`.

## Cron Schedule
While OLIV continues to use Laravel's default `php artisan schedule:run` command to execute scheduled tasks, any tasks defined in `routes/console.php` will still function as expected.

To define scheduled tasks within a plugin, simply create a `routes/console.php` file inside your plugin directory at:
plugins/<VendorName>/<PluginName>/routes/console.php

Laravel will automatically load and run these tasks when the scheduler is triggered.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

##  InertiaJS
Lear more about [inertia](https://inertiajs.com/), [GitHub](https://github.com/inertiajs/inertia/tree/master)
