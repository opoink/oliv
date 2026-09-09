# Vite integration

Implementation: src/resources/vite.config.js and src/resources/viteplugins/*.js. These files are copied to application root locations during [installation](../getting-started/installation.md); there is no package-root viteplugins directory in this checkout.

## Supplied configuration

Plugin order is:

1. transformFileImport(), explicitly enforce: pre.
2. vueComponentInjectorPlugin().
3. Laravel Vite plugin.
4. Vue plugin.

Inputs are resources/css/admin.app.scss, resources/css/client.app.scss, and resources/js/app.js. SSR entry is resources/js/ssr.js. Laravel refresh is true. Vue transformAssetUrls uses base: null and includeAbsolute: false.

Aliases **Plugins** and **@@Plugins@@** both resolve to application plugins/. The [theme transform](theme-overrides.md) replaces matching plugin module source; the [comment injector](component-injection.md) applies compiled layouts metadata.

## Build workflow

~~~bash
php artisan oliv:plugins-update --no-interaction
npm run dev
~~~

For production:

~~~bash
php artisan oliv:plugins-update --no-interaction
npm run build
~~~

Or use npm run build:ssr for client and SSR. Compilation must precede Vite: the injector imports compiled.plugins.config.json at module load, and the app imports generated page/global modules.

The [page asset helper](../helpers/inertia-render.md) assumes public/hot and public/build/manifest.json with /build/ URLs. Custom build locations require corresponding application integration; there is no OLIV configuration bridge for them.

## Limitations

The supplied configuration and plugins use __dirname despite ESM import syntax, relying on the Vite configuration-loading environment. Direct standalone Node execution is not established.

Compiled metadata is imported once; declaration changes are not dynamically reread by the injector. Recompile and restart/rebuild rather than assuming config edits hot-reload.

No OLIV install step builds assets or starts Vite. Generated absolute paths can make copied compiler outputs unsuitable on another filesystem.

---
[Assets](asset-resolution.md) · [CSS](css-loading.md) · [SSR](ssr.md) · [Documentation index](../README.md)
