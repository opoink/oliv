# Plugin structure and registration

Implementation: functions.php, src/Lib/Plugin/UpdatePlugin.php, src/resources/plugins/config.sample.json.

An enabled identifier such as **Opoink_Email** maps through getPluginDir($plugin) to application **plugins/Opoink/Email**. All underscores become directory separators. [Installation](../getting-started/installation.md) adds Composer's Plugins\ namespace mapping.

~~~text
plugins/
  config.json
  Vendor/Plugin/
    config.json
    config/
      adminmenu.php
      roleresource.php
    Providers/
      AppServiceProvider.php
    EventListeners/
      EventList.php
    routes/
      web.php
      console.php
    migrations/
    etc/admin/system.php
    resources/
      layout/<route-name>.json
      css/admin.app.scss
      css/client.app.scss
      js/Pages/
      js/GlobalComponents/
~~~

These are discovery locations, not mandatory contents of every plugin. Controllers, models, libraries, bookmark JSON, and other resources can be organized below the plugin; the compiler does not scan all of them.

## Registration contract

~~~json
{"plugins": ["Opoink_Liv", "Vendor_Plugin"]}
~~~

getPluginsConfig() decodes plugins/config.json into a DataObject and memoizes it globally. A missing file gives an empty plugins array. Invalid JSON is not validated safely before the typed DataObject constructor. Use a well-formed object containing an array of strings.

No automatic filesystem discovery enables a plugin. No dependency sorting, semantic version comparison, installation hooks, or enable/disable database table exists. List order is meaningful.

Layout discovery additionally validates names against letters/digits separated by one or more underscores and deduplicates directories. The broader compiler does not apply the same validation.

## Metadata

Bundled config.json files contain name, vendor, version, and the misspelled **autor**. These are copied into the [compiled JSON](plugin-update.md); no metadata key activates a plugin or proves compatibility. The optional layouts map drives [Vite comment injection](../frontend/component-injection.md), not generated page layouts.

## Developer workflow

Create the directory and matching namespace, add the identifier to the registry, write the needed declarations, run [plugin update](../cli/plugins-update.md), inspect outputs, then migrate/build as appropriate. Keep Opoink_Liv before bundled dependent plugins. This is an operational recommendation, not dependency validation.

---
[Conventions](../reference/conventions.md) · [Documentation index](../README.md)
