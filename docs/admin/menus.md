# Admin menus

Implementation: src/Lib/Plugin/MergeAdminMenu.php, UpdatePlugin.php, and src/resources/plugins/Opoink/Liv/config/adminmenu.php and resources/js/Components/Admin/SideNav.vue.

Each enabled plugin can return menu data from config/adminmenu.php. [Compilation](../plugins/plugin-update.md) writes config/adminmenus.php; [Inertia middleware](../frontend/inertia.md) shares it only on admin-prefix routes.

## Schema

~~~php
return [
    [
        'name' => 'example',
        'route' => null,
        'label' => 'Example',
        'fa_icon' => 'fa-solid fa-folder',
        'is_active_menu_url' => '/example',
        'is_active_menu_name' => 'example',
        'role_resource' => 'example_access',
        'children' => [
            [
                [
                    'name' => 'example.group',
                    'title' => 'Management',
                    'role_resource' => 'example_access',
                    'links' => [
                        [
                            'name' => 'example.list',
                            'route' => '/example',
                            'label' => 'Records',
                            'role_resource' => 'example_access',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
~~~

children is **an array of columns, each containing named groups**, not the recursive node shape used by page layouts. Groups contain links. A top-level nonempty route renders a direct link; null/empty route renders a flyout. Routes are admin-relative URL suffixes, not Laravel route names.

## Merge API

Opoink\Oliv\Lib\Plugin\MergeAdminMenu exposes:

- mergeByName(array $base, array $append): array
- mergeChildrenRecursively(array $baseChildren, array $appendChildren): array
- removeMarkedEntries(array $array): array

Names identify items within the current merged list. Unnamed append items are skipped, despite a contrary method comment. Later scalar values overwrite earlier ones. links recursively merge by name. children normalize columns and merge corresponding column indexes.

**Column limitation:** child merging iterates existing base columns and assumes a matching append index. It does not robustly append extra columns; missing counterparts can cause errors. Likewise, merging children/links into an existing item assumes corresponding base arrays exist.

remove: true removes an item. Cleanup recursively visits column children and links after each plugin's configuration collection. A later plugin can add the same name again. There is no sort_order implementation in this menu merger; preserve list insertion order.

SideNav checks role_resource when present. When absent, it shows an item only if shared roles_resource is "*", so missing resources can hide groups from ordinary admins. Frontend visibility is distinct from [action authorization](authentication.md).

---
[Tabs and shell](tabs-and-layouts.md) · [Documentation index](../README.md)
