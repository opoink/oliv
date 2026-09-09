# Tabs and the admin shell

Implementation: src/resources/plugins/Opoink/Liv/resources/js/Layouts/Admin/Default.vue, Components/Admin/SideNav.vue, MainHeader.vue, States/admin.side.tabs.js, States/admin.side.nav.js, and Pages/Admin/Settings/Index.vue.

Default.vue composes SideNav, MainHeader, a default content slot, Loader and Toast. It is a reusable wrapper:

~~~vue
<script setup>
import Default from '@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue';
</script>
<template>
    <Default><p>Page content</p></Default>
</template>
~~~

The [generated layout system](../layouts/page-layout.md) can import this same wrapper. It does not add layouts automatically to every page.

MainHeader displays shared admin firstname/lastname, links to edit that admin ID, and GET logout. SideNav consumes [compiled menus](menus.md), uses reactive active-menu state, jQuery click handlers and document wheel handling.

## Two tab mechanisms

The settings page uses query parameters **tab** and **section** to select compiled system groups. This is explained in [system configuration](system-configuration.md).

Separately, AdminSideTabs is an exported class with reactive singleton adminSideTabs:

| API | Behavior |
| --- | --- |
| setActiveTab(key) | Sets isActiveTab |
| setActiveParent(key) | Toggles a key in activeParents |
| getAdminUrl() | Returns current browser pathname/query with active_tab and encoded active_parents |
| reset() | Clears state |

active_parents is a URL-safe Base64 encoding of comma-separated active keys. getAdminUrl requires window; it does not itself navigate or restore incoming parameters. When state is empty it does not delete previously present query keys.

The global Filters plugin also has $isActiveTab with default query parameter active-tab and a global route() dependency. That is a third, older helper, not an alias for AdminSideTabs. The supplied shell does not expose Ziggy routes.

These mechanisms are conventions found in bundled UI, not a unified server-side tab registry.

---
[UI helpers](../frontend/helpers-and-components.md) · [Documentation index](../README.md)
