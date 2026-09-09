# Plugin compilation and generated files

Implementation: Opoink\Oliv\Lib\Plugin\UpdatePlugin, src/Lib/Plugin/UpdatePlugin.php.

The public entry points are __construct(PluginsUpdate $command) and executeUpdate() (no declared return type; implicit null). The command supplies progress output. This operation writes application files and replaces an event cache entry.

## Discovery and execution order

For each identifier from [plugins/config.json](plugin-structure.md), the compiler collects config files, providers, events, metadata JSON, conventional SCSS, system definitions, page globs, global-component directories, and route paths.

It then saves config, menus, providers, compiled metadata; compiles [page layouts](../layouts/layout-middleware.md); writes SCSS, page globs, globals, web/console route includes, system definitions; and finally replaces [event registrations](events.md).

~~~mermaid
flowchart TD
    Registry["Ordered plugin registry"] --> Collect["Collect declarations"]
    Collect --> PHP["Write PHP config and providers"]
    PHP --> Metadata["Write compiled metadata JSON"]
    Metadata --> Layout["Compile page layouts"]
    Layout --> Frontend["Write SCSS, page glob, globals"]
    Frontend --> Routes["Write route includes"]
    Routes --> Settings["Compile settings"]
    Settings --> Events["Replace event cache"]
~~~

## Input/output map

| Input in each plugin | Application output |
| --- | --- |
| config/*.php except adminmenu | config/plugins.php, keyed by basename |
| config/adminmenu.php | config/adminmenus.php |
| Providers immediate entries | Appended bootstrap/providers.php |
| config.json | plugins/compiled.plugins.config.json array |
| resources/layout/*.json | storage/framework/vue/pages/*.vue |
| resources/css/admin.app.scss | resources/css/admin.app.scss with ordered @use statements |
| resources/css/client.app.scss | resources/css/client.app.scss |
| resources/js/Pages/**/*.vue | resources/js/plugin.pages.js glob declaration |
| resources/js/GlobalComponents descendants | resources/js/vue.global.components.js imports and registrations |
| routes/web.php | routes/plugin_web.php require_once list |
| routes/console.php | routes/plugin_console.php require_once list |
| etc/admin/system.php | storage/app/private/plugins/etc/admin/system*.php and section files |
| EventListeners/EventList.php | Laravel cache key plugin_event_listeners |

Page globs also include application Pages and storage-generated pages. Global imports and SCSS statements contain filesystem-derived absolute paths; compile artifacts on the deployment filesystem before building if paths differ.

## Removal and failures

Most outputs are replaced, even when the new set is empty. Providers are append-only. Old settings section files are not pruned. Generated layouts delete obsolete files only when they have the generator ownership marker.

The compiler performs no general declaration validation, atomic multi-file write, or rollback. Invalid PHP/JSON, missing classes, unexportable config values, or filesystem failures can interrupt compilation.

This is not a version updater. [Installation](../getting-started/installation.md), database migrations, npm builds, and Laravel cache refresh are separate.

---
[Configuration semantics](configuration.md) · [Cache lifecycle](../reference/caching.md) · [Documentation index](../README.md)
