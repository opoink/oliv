# Plugin configuration merging

Implementation: src/Lib/Plugin/UpdatePlugin.php, MergeAdminMenu.php, MergeSystemConfig.php, Layout.php.

OLIV has multiple configuration languages; their merge rules are not interchangeable.

| Declaration | Identity/merge rule | Consumer |
| --- | --- | --- |
| config.json | Each plugin object appended to compiled JSON | Vite injector |
| config/*.php | Group by filename; array_merge_recursive across plugins | config('plugins.<basename>') |
| config/adminmenu.php | Named items; specialized children/links merging | Admin menu |
| etc/admin/system.php | Named siblings, recursive children, later values | System settings |
| resources/layout/<route>.json | Page-wide names, first component/parent, later attributes | Vue page generator |
| EventListeners/EventList.php | Group by name, append and sort listeners | Custom dispatcher |

## Ordinary PHP configuration

~~~php
// plugins/Vendor/Plugin/config/catalog.php
return [
    'label' => 'Catalog',
    'features' => ['search'],
];
~~~

After [compilation](plugin-update.md), read config('plugins.catalog.label'). It is not automatically available as config('catalog.label').

The first file becomes the initial value. Subsequent same-basename files use array_merge_recursive: duplicate scalar keys become arrays, and numeric lists append. This is not last-plugin-wins replacement.

The scan is immediate and includes entries without checking extension or file type. Keep config/ limited to suitable PHP files returning exportable arrays. The result is written with var_export; do not rely on closures or arbitrary service objects surviving compilation.

## Specialized declarations

[Menus](../admin/menus.md), [system configuration](../admin/system-configuration.md), [page layouts](../layouts/page-layout.md), and [Vite injection](../frontend/component-injection.md) each have dedicated schemas.

System definition PHP executes at compile time, including option-class calls. Database setting overrides are read later at runtime. A compiler-generated default change does not replace an existing database override.

[Installation](../getting-started/installation.md) supplies templates; compilation does not invalidate Laravel's cached config automatically.

---
[Plugin structure](plugin-structure.md) · [Documentation index](../README.md)
