# php artisan oliv:install

Implementation: Opoink\Oliv\Console\Commands\Install, src/Console/Commands/Install.php.

~~~bash
php artisan oliv:install --no-interaction
~~~

Signature: oliv:install. No package-specific options. handle(): void catches Exception and prints its message; it provides no explicit reliable failure status or rollback.

## Changes

1. Adds [environment defaults](../getting-started/configuration.md), forcing file sessions/cache.
2. Rewrites Composer JSON to add Plugins\ mapped to plugins/.
3. Offers composer dump-autoload, default yes, executed through exec.
4. Recursively copies templates.
5. Rewrites dependency entries and build:ssr in package.json.
6. Prints npm-install and configuration-merge reminders.

| Template under src/resources | Application target |
| --- | --- |
| plugins, routes, app, resources, config, public, viteplugins | Corresponding root directory |
| vite.config.js | vite.config.js |
| ecosystem.config.js | ecosystem.config.js |
| jsconfig.json | jsconfig.json |

**Overwrite behavior:** recursive copies replace existing files, including routes and the base controller. Only the three individually copied root configuration files receive .bak copies. Later runs can overwrite those backups. Composer JSON, package JSON, .env, and copied trees have no general backup.

Dependencies include Inertia, server rendering, editors, GrapesJS extensions, date/filter utilities, jQuery, SortableJS and Vite tooling. build:ssr becomes "vite build && vite build --ssr". npm installation is not executed.

The active registry, database setup, [plugin compilation](plugins-update.md), migrations and asset builds remain separate [installation steps](../getting-started/installation.md). The provider's generic publish mapping points to a missing source; this direct-copy command is the implemented install path.

---
[Installation workflow](../getting-started/installation.md) · [Documentation index](../README.md)
