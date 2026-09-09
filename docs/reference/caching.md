# Cache and generated-state lifecycle

OLIV uses multiple stores with different invalidation rules.

| State | Writer/reader | Refresh behavior |
| --- | --- | --- |
| Global pluginsConfig DataObject | getPluginsConfig in functions.php | First load per PHP process/global lifetime; no reset API |
| Static manifest cache | inertiaRender | Refresh when path or filemtime changes |
| plugin_event_listeners Laravel cache | Compiler / Liv Event | Forgotten then stored forever by plugin update |
| Event instance events property | Event::getEvents | Reuses truthy value |
| roles_resource session entry | getRolesResource | First role lookup; logout flushes session |
| SystemConfig loadedValue | getValue | Explicit resetLoadedValue |
| AdminListing bookmark/columns | Service instance | Reused once initialized |
| DataObject underscore cache | _underscore | Static transformation cache |
| scriptloader URL map | Browser helper | Marked before load; failure clears |
| Public image files | Media controller/web server | No supplied expiration cleanup or source invalidation |

Implementation: functions.php, src/Lib/DataObject.php, src/Lib/Plugin/UpdatePlugin.php and bundled Liv/Media libraries.

## Generated files are a separate cache-like layer

[Plugin compilation](../plugins/plugin-update.md) writes PHP config, provider/route lists, JS glob/global modules, SCSS, settings metadata, and generated pages. Laravel config/route/view caches and Vite bundles can still reflect older artifacts.

After changing declarations, compile before building. If clearing Laravel's application cache, rebuild the [custom event registry](../plugins/events.md) afterward: there is no runtime reconstruction fallback. Refresh Laravel configuration/route caches according to the host deployment process; plugin update does not do so automatically.

Provider entries are append-only. Old setting-section files remain. Owned obsolete layout files are pruned, but unmarked pages remain and can still influence inertiaRender's existence-based selection.

The listing service contains commented-out Cache::rememberForever code. Its active behavior queries the database and memoizes on the object; Cache::forget calls alone do not establish a working distributed bookmark cache.

The [Media cache](../bundled-plugins/media.md) sends a one-year client cache lifetime. Changing source files does not automatically remove generated public variants.

Long-lived PHP/SSR/browser processes can retain state beyond one logical action. No package-wide reset mechanism is provided.

---
[Known limitations](known-limitations.md) · [Documentation index](../README.md)
