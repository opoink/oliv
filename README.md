## Getting Started

## Installation
- Install Laravel without a starter kit, see Laravel's [documentation](https://laravel.com/docs)
- 	`Would you like to install a starter kit? [No starter kit]:`
- 	`Which database will your application use? [mysql]`
- 	`Default database updated. Would you like to run the default database migrations? (yes/no) [no]`
- Install oliv package `composer require Opoink/Oliv`
- Let Laravel discover the Oliv package `php artisan package:discover`
- Create a `.env` file and copy it from `.env.example` (optional) change the value of the `VITE_ADMIN_URL` of your choice.
- (optional) Change the value of the `VITE_INERTIA_SSR_PORT` in `ecosystem.config.js` of your choice
- Update database host, user, pass, name
- create file `plugins/config.json` you can copy it from `plugins/config.sample.json`
- run the command `php artisan oliv:plugins-update` this will compile and update all plugins set at the config.json file
- Optional `php artisan migrate` to migrate database
- run command `php composer.phar upgrade` or `composer upgrade`
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
			public function handle(\App\Lib\DataObject $data){
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
  
Note: We are not using the default Laravel event listeners and dispatchers; instead, we are using custom events.



## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

##  InertiaJS
Lear more about [inertia](https://inertiajs.com/), [GitHub](https://github.com/inertiajs/inertia/tree/master)
