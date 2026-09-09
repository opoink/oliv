# Vite comment-based component injection

Implementation: src/resources/viteplugins/vue.component.injector.plugin.js, src/resources/resources/js/common.js, and Liv's settings page template.

This is separate from [JSON page generation](../layouts/page-layout.md). It edits an existing plugin Vue source string during Vite transform.

## Declaration

Place a layouts map in a plugin's config.json:

~~~json
{
  "name": "Example",
  "vendor": "Vendor",
  "layouts": {
    "plugins/Opoink/Liv/resources/js/Pages/Admin/Settings/Index.vue": [
      {
        "data-v-ref": "settings-tabs",
        "position": "before",
        "component": "plugins/Vendor/Example/resources/js/Components/Notice.vue",
        "attr": "class=\"notice\""
      }
    ]
  }
}
~~~

The target file must contain both insertion markers:

~~~vue
<script setup>
/** component import on build rollup will be injected here */
</script>
<template>
    <!-- component-inject-settings-tabs-before -->
</template>
~~~

Liv's settings template provides settings-tabs-before and settings-tabs-after markers.

## Transform behavior

The injector imports plugins/compiled.plugins.config.json at module load. For a matching plugins/ module whose extension appears to be vue, it processes config objects and layout arrays in order.

Each entry requires data-v-ref, position and component to be defined. attr is used only if it is a string; it is raw Vue attribute source. Imports use the application root plus component path. getComponentName derives a local binding from path segments; it is not the global-component naming helper.

Tags targeting the same marker are concatenated in contribution order. Every exact matching HTML comment is replaced. The script import marker is replaced with collected imports. There is no DOM search for a real data-v-ref attribute.

## Limitations

- "before" and "after" are marker suffixes, not semantic insertion relative to DOM nodes.
- Missing HTML markers leave tags absent; missing script marker leaves imports absent. No warning validates either.
- Imports are not deduplicated; duplicate generated names can break compilation.
- No removal, nested merge, sort_order, or conflict resolution exists.
- Only plugin Vue paths are considered; application/generated pages are not targets.
- Path handling is less normalized than the theme transform, and query-suffixed IDs may not match.
- Configuration changes require [recompilation](../cli/plugins-update.md) and Vite restart/rebuild.

These declarations are trusted plugin code. Arbitrary attr strings can contain expressions.

---
[Theme transforms](theme-overrides.md) · [Vite](vite.md) · [Documentation index](../README.md)
