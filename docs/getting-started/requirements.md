# Requirements and version scope

Implementation: composer.json, src/Console/Commands/Install.php, src/resources/vite.config.js.

| PHP dependency | OLIV constraint |
| --- | --- |
| PHP | ^8.3 |
| laravel/framework | ^11.31 or ^12.0 or ^13.0 |
| inertiajs/inertia-laravel | ^2.0 |
| laravel/sanctum | ^4.0 |
| phpmailer/phpmailer | ^6.9.2 |
| gumlet/php-image-resize | ^3.0 |

These constraints do not prove every combination works. The inspected application's Composer report identifies OLIV as dev-oliv-1.x.x at 0a6d7a4 and Laravel as 13.30.1. Transitive dependencies can impose stricter platform requirements.

The [installer](../cli/oliv-install.md) writes JavaScript dependency ranges including Inertia Vue 2.0.0, Vue server renderer ^3.4.19, Vite ^6.0.5, Laravel Vite plugin ^1.1.1, Vue Vite plugin ^5.2.1, Sass, Axios, jQuery, SortableJS, TinyMCE, GrapesJS and its plugins. It does not run npm install. These declarations do not establish exact resolved npm versions.

The old README lists Node 18.17.0/23.5.0 and MySQL 9.1.0 as development context. OLIV does not enforce those exact versions. Use versions compatible with resolved dependencies.

## Operational assumptions

- Laravel already has .env, composer.json, and package.json.
- [Installation](installation.md) provides application plugin autoloading.
- Compilation can write configuration, bootstrap, routes, frontend, and storage files.
- Migrations use MySQL-oriented column placement and timestamp behavior; portability is unverified.
- Liv's 2014 users migration **alters an existing users table**. Its filename can sort before a fresh host users migration.
- [Media](../bundled-plugins/media.md) requires PHP file-information/image functions and GD codecs.
- [SSR](../frontend/ssr.md) needs a running Node rendering process.
- The admin shell expects Bootstrap CDN assets, Font Awesome, and a TinyMCE public asset.

No installer, database migration, build, or SMTP delivery was executed for this documentation. Runtime compatibility beyond inspection remains unverified.

---
[Installation](installation.md) · [Documentation index](../README.md)
