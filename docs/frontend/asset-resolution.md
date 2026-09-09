# Asset resolution

Implementation: src/resources/vite.config.js, viteplugins/import.plugin.js, resources/js/app.js and ssr.js, and functions.php.

## Different path languages

| Context | Example | Meaning |
| --- | --- | --- |
| PHP Inertia component | Opoink/Liv/resources/js/Pages/Admin/Index | Plugin-relative page without .vue |
| Vue import | @@Plugins@@/Opoink/Liv/resources/js/Components/Toast.vue | Vite alias import |
| Layout component | Plugins/Vendor/Plugin/resources/js/Components/Card.vue | Default-export module specifier |
| Injector target | plugins/Opoink/Liv/resources/js/Pages/Admin/Settings/Index.vue | Root-relative source-file key |
| Manifest key | plugins/Opoink/Liv/resources/js/Pages/Admin/Index.vue | Client build entry |
| Media source | Opoink/Media/resources/images/image-empty.webp | Storage/plugin lookup handled by Media |

Both plugin aliases resolve to application plugins/. They are not PHP namespace aliases or URLs.

The [Vue page resolver](vue.md) prefers plugin pages over application Pages, then generated storage pages for a given name. The [PHP helper](../helpers/inertia-render.md) separately chooses the first matching manifest candidate in equivalent location order.

[Theme overrides](theme-overrides.md) retain the original module ID while replacing source, so relative imports remain relative to that original plugin module. This is not a filesystem overlay for every file access.

Static public files use the root shell's asset() paths. [Media image URLs](../bundled-plugins/media.md) are handled by a separate controller/cache system and do not inherit Vite theme resolution.

Production page CSS URLs are hardcoded to /build/ plus manifest paths. They do not use asset() or an asset host. Explicit page_assets bypass lookup but need a consumer.

---
[Vite](vite.md) · [CSS loading](css-loading.md) · [Documentation index](../README.md)
