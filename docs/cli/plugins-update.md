# php artisan oliv:plugins-update

Implementation: src/Console/Commands/PluginsUpdate.php and src/Lib/Plugin/UpdatePlugin.php.

~~~bash
php artisan oliv:plugins-update --no-interaction
~~~

Signature: oliv:plugins-update. No plugin-name, dry-run, version, or rollback option. handle(): void constructs UpdatePlugin($this) and calls executeUpdate().

Every configured plugin is compiled in list order. See the [complete input/output map](../plugins/plugin-update.md).

Existing compiler outputs are overwritten; providers are appended; obsolete owned generated pages can be deleted; event cache is replaced. Sequential writes have no transaction, so failure can leave partial output.

~~~bash
php artisan oliv:plugins-update --no-interaction
npm run build:ssr
~~~

Use npm run build when only producing client assets. Run compilation after declaration changes and before Vite evaluates its page globs.

The command does not download plugins, compare versions, invoke per-plugin upgrade hooks, migrate, clear Laravel config/routes/views, or run npm. [Cache management](../reference/caching.md) remains separate.

---
[Compiler details](../plugins/plugin-update.md) · [Installation](../getting-started/installation.md) · [Documentation index](../README.md)
