# Vue pages and global components

Implementation: src/resources/resources/js/app.js, ssr.js, common.js, and src/Lib/Plugin/UpdatePlugin.php.

## Page resolution

Both browser and SSR entry points resolve a component name in this order:

~~~text
../../plugins/<name>.vue
./Pages/<name>.vue
../../storage/framework/vue/pages/<name>.vue
~~~

They test membership in the generated PluginPages map, then call resolvePageComponent. There is no further custom fallback if all entries are absent. Names are used literally by these JS resolvers; use forward slashes and omit .vue.

[Plugin compilation](../plugins/plugin-update.md) writes resources/js/plugin.pages.js using lazy import.meta.glob for application Pages, generated pages, and enabled plugins' resources/js/Pages/**/*.vue. Adding files after a production build does not extend the built map.

The PHP [render helper](../helpers/inertia-render.md) can first replace the name with a generated route-based name, which is then subject to this resolver order.

## Global components

The compiler recursively scans resources/js/GlobalComponents in enabled plugins. It recognizes extensions case-insensitively, generates eager imports, and writes RegVueGlobalComponents(app). Browser and SSR setup both invoke it.

The naming helper removes application plugins/ and resources/js/, capitalizes path words, strips the extension and separators. For example:

~~~text
plugins/Opoink/Liv/resources/js/GlobalComponents/Admin/SystemConfigFieldGroups.vue
→ OpoinkLivGlobalComponentsAdminSystemConfigFieldGroups
~~~

~~~vue
<OpoinkLivGlobalComponentsAdminSystemConfigFieldGroups
    :field_groups="groups"
/>
~~~

The [settings UI](../admin/system-configuration.md) uses this registration recursively. Recompile after adding/removing globals. There is no collision detection or sanitization into guaranteed-valid JavaScript identifiers; prefer simple alphanumeric filenames.

Generated global imports are filesystem paths and load eagerly; one broken global import can affect every page and SSR.

## App setup

Both apps install Inertia and Filters. Browser setup exposes Axios as window.axios and sets X-Requested-With. An app-wide mounted mixin tracks previous/current URLs only for components declaring isPage. It stores values on window.url_history, used by [navigation helpers](helpers-and-components.md).

The browser entry uses createApp; SSR uses createSSRApp and renderToString. Neither entry supplies a page_assets consumer.

---
[Vite](vite.md) · [Installation](../getting-started/installation.md) · [Documentation index](../README.md)
