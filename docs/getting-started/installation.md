# Installation workflow

OLIV copies application templates and then compiles enabled plugins. Read the [overwrite behavior](../cli/oliv-install.md) before running the installer on an existing project.

~~~bash
composer require opoink/oliv
php artisan package:discover --no-interaction
php artisan oliv:install --no-interaction
~~~

The installer's Composer-autoload prompt defaults to yes. It adds the Plugins\ autoload mapping and copies src/resources subtrees. It requests a later manual npm install.

Create application plugins/config.json from plugins/config.sample.json:

~~~json
{
  "plugins": ["Opoink_Liv", "Opoink_Cms", "Opoink_Email", "Opoink_Media"]
}
~~~

This order controls [configuration merging](../plugins/configuration.md), [layout contributions](../layouts/page-layout.md), routes and SCSS. The installer copies only the sample, not an active registry.

Configure the database and [environment](configuration.md), then compile:

~~~bash
php artisan oliv:plugins-update --no-interaction
npm install
~~~

Before migrating, resolve the [users-table prerequisite](requirements.md) and inspect [migration effects](../cli/migrations-and-scheduler.md). The bundled 2014 users migration can run before a newer host create-users migration.

~~~bash
php artisan migrate --no-interaction
npm run build
~~~

The admins migration inserts **admin@domain.com**, password **admin**, as a super_admin. Change this starter credential during initialization.

## Development and SSR

~~~bash
php artisan serve
npm run dev
~~~

For [SSR](../frontend/ssr.md):

~~~bash
npm run build:ssr
php artisan inertia:start-ssr
~~~

Compilation must precede builds so Vite sees generated pages and imports. Changes to plugin registration, declarations, globals, events, or routes can require [recompilation](../plugins/plugin-update.md) and cache refresh.

## Installation is not an upgrade manager

Rerunning oliv:install overwrites application copies. There is no transaction or general backup. oliv:plugins-update compiles local plugins; it does not download versions, copy changed templates, run migrations, or build assets.

Implementation: src/Console/Commands/Install.php, src/resources/plugins/config.sample.json, src/Lib/Plugin/UpdatePlugin.php.

---
[Installer command](../cli/oliv-install.md) · [Documentation index](../README.md)
