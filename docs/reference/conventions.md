# Developer conventions

This page distinguishes implementation requirements from bundled patterns. Sources: functions.php, src/Lib/Plugin/UpdatePlugin.php, Layout.php, and src/resources/plugins.

| Convention | Status | Reason |
| --- | --- | --- |
| Register identifiers in plugins/config.json | Required for compiler discovery | No automatic enablement scan |
| Map underscores to plugin directories | Required by getPluginDir | Opoink_Email → plugins/Opoink/Email |
| Plugins\ namespace maps to plugins/ | Installed autoload contract | Composer mapping added by installer |
| Simple alphanumeric Vendor_Plugin identifiers | Required by layout discovery | Layout has a stricter regex than compiler |
| Providers directly under Providers/ | Required for automatic provider collection | No recursive scan/type check |
| Plugin migrations directly under migrations/ | Required for automatic migration path discovery | database/migrations is not scanned |
| config/*.php returning arrays | Required for expected compilation | Included then merged/exported |
| etc/admin/system.php | Required for settings discovery | Exact path |
| resources/layout/<route-name>.json | Required for page generation | Route-derived file lookup |
| resources/js/Pages and GlobalComponents | Required for automatic frontend discovery | Compiler-generated globs/scans |
| admin.app.scss / client.app.scss | Required for automatic area-style collection | Exact conventional filenames |
| Extend Liv Models\Model | Optional bundled convention | Opts into custom events/transactions |
| Http/Controllers/Admin and Client | Bundled organizational convention | Compiler does not scan controllers |
| propsdata page prop | Bundled UI convention | Listing/editor components expect it; not universal |
| Lib/Bookmarks/*.json | Bundled organization | Caller passes a path explicitly |
| config.json name/vendor/version/autor | Bundled metadata | No version/dependency enforcement |
| Named resources and explicit backend checks | Recommended | Menu hiding alone does not authorize routes |
| Unique names and simple component filenames | Recommended | Avoid layout/global registration collisions |

Use [configuration-specific merge rules](../plugins/configuration.md), not a universal "last plugin wins" assumption. Page layouts use first component/parent definitions; ordinary config uses recursive array merge; menus and settings have their own behavior.

[Installation](../getting-started/installation.md) creates application copies. Durable customizations belong in those application plugins or [theme overrides](../frontend/theme-overrides.md), with awareness that reinstalling copies over them.

Run [plugin update](../cli/plugins-update.md) when declarations change; build after generated page/import changes. Existing providers and old section files may require manual lifecycle management.

There is no confirmed generic plugin installation hook, repository abstraction, API resource base class, client-auth system, or automatic CMS publishing convention in this package.

---
[Directory structure](directory-structure.md) · [Documentation index](../README.md)
