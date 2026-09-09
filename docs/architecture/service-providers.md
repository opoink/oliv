# Providers and dependency injection

Implementation: src/Providers/AppServiceProvider.php, src/Lib/Plugin/UpdatePlugin.php, src/Facades, and src/resources/plugins/Opoink/Liv/Providers/AppServiceProvider.php.

## Package provider

Composer discovery registers **Opoink\Oliv\Providers\AppServiceProvider**. Its register(): void is empty. Its boot(): void sets schema default string length to 191, forces database.connections.mysql.engine to InnoDB, appends HandleInertiaRequests to the web group, declares a publish mapping, and registers five console classes when running in console.

The publish source **src/config/oliv.php does not exist**. The actual template is src/resources/config/oliv.php, copied by [installation](../cli/oliv-install.md). The PageLayout middleware registration line is commented out.

## Plugin providers

The [compiler](../plugins/plugin-update.md) scans each plugin's immediate Providers directory. Each entry's filename becomes Plugins\<Vendor>\<Plugin>\Providers\<Filename>. There is no PHP-extension, class-existence, or recursive-directory validation.

Names are appended without duplicates to existing bootstrap/providers.php. Removing a plugin does not remove its previous provider entry. Keep this directory limited to provider PHP files.

Liv's provider creates the admin session guard and Eloquent provider, translates underscores in oliv.auth_admin_user into namespace separators, and aliases adminauth to AdminAuthenticated. See [authentication](../admin/authentication.md).

## Container and facades

Ordinary Laravel container resolution supplies layouts, listeners, options, and constructor dependencies. There is no separate OLIV dependency-injection registry.

| Facade namespace | Facade | Target |
| --- | --- | --- |
| Opoink\Oliv\Facades | Dirmanager | Opoink\Oliv\Lib\Dirmanager |
| Opoink\Oliv\Facades | MergeAdminMenu | Opoink\Oliv\Lib\Plugin\MergeAdminMenu |
| Plugins\Opoink\Liv\Lib\Facades | AdminListing, QryFilter, SystemConfig, Event | Same class name under Plugins\Opoink\Liv\Lib |

The accessors return concrete class names. Supplied providers do not explicitly bind these libraries as singletons. Stateful facade calls and separately resolved instances need not share state.

~~~php
$layout = app(\Opoink\Oliv\Lib\Plugin\Layout::class);
$tree = $layout->mergeLayouts($base, $extension);
~~~

See the [tree contract](../layouts/page-layout.md) and [filesystem helpers](../helpers/helper-functions.md).

---
[Architecture](overview.md) · [Documentation index](../README.md)
