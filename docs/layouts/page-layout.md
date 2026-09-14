# Page layout declarations, hierarchy, and composition

Implementation: `src/Lib/Plugin/Layout.php`. OLIV composes generated Vue pages from JSON definitions contributed by enabled plugins. A layout is selected from the Inertia route/page name, so plugins can extend another plugin's component tree without editing that plugin.

## Handles and file names

Put definitions in `plugins/Vendor/Plugin/resources/layout/<handle>.json`. A dotted route name resolves every exact prefix, broadest first. Only files that exist are read.

| Page name | Resolved handles |
| --- | --- |
| `client.index` | `client`, `client.index` |
| `client.catalog.products` | `client`, `client.catalog`, `client.catalog.products` |
| `admin.catalog.products.edit` | `admin`, `admin.catalog`, `admin.catalog.products`, `admin.catalog.products.edit` |

For `client.catalog.products`, OLIV considers `client.json`, `client.catalog.json`, then `client.catalog.products.json`. Missing intermediate files are normal. Each handle is processed in configured plugin order before the next, more-specific handle is processed. This makes the exact page the final contribution while preserving the existing merge rules below.

The first route-name segment selects a layout family; `client.json`, `admin.json`, `onecol.json`, and `anotherdefaultlayout.json` are equally ordinary broad handles. They are not global magic filenames. For example, `onecol.login` resolves `onecol.json` then `onecol.login.json`.

## Declaration schema

~~~json
[
  {
    "name": "AppHeader",
    "component": "@@Plugins@@/Vendor/Plugin/resources/js/Components/AppHeader.vue",
    "attr": {"class": "site-header"},
    "children": [
      {
        "name": "Navigation",
        "component": "@@Plugins@@/Vendor/Plugin/resources/js/Components/Navigation.vue",
        "slot_name": "primary_navigation"
      }
    ]
  }
]
~~~

| Key | Meaning |
| --- | --- |
| `name` | Required stable logical merge key. It is page-global, including across nesting levels. |
| `component` | Optional module path for the default export. Omit it for a locator-only contribution. |
| `import` | Legacy single default/named import; used only when `component` is absent. |
| `attr` | Optional attribute object. Later values replace earlier values by key. |
| `children` | Recursive array or legacy keyed object. |
| `slot_name` | Optional nonempty string naming the parent Vue slot for this child. |
| `remove` | Only literal `true` removes the node/subtree; later non-true values re-enable it. |

## Recursive merge and injection

`name` is the identity used to merge nodes. The first valid component/export and first parent relationship win; later contributions can add children and replace individual attributes. Roots and sibling children retain first-contribution order. A locator-only node such as the following targets an existing component without redefining it:

~~~json
[{"name":"Homepage","children":[{"name":"AppHeader","children":[{"name":"Categories","component":".../Categories.vue"}]}]}]
~~~

Core can define `Homepage → AppHeader → AppMain → AppFooter`; Catalog can contribute `Homepage → AppHeader → Categories`. The final tree has one `Homepage` and one `AppHeader`, with the Catalog child injected beneath it. Use distinct names for intentional duplicate component instances. A locator unresolved after all handles/plugins are merged is skipped with its subtree.

Because names are page-global merge keys, use the same logical root and target names throughout one layout family. Do not define the same `AppHeader` name beneath unrelated roots in a single merged page.

## Named Vue slots

A child without `slot_name` remains a normal/default-slot child. A child with `slot_name` is wrapped in Vue's long-form named-slot syntax:

~~~json
{"name":"Categories","component":".../Categories.vue","slot_name":"primary_navigation"}
~~~

~~~vue
<ParentComponent>
    <template v-slot:primary_navigation>
        <Categories />
    </template>
</ParentComponent>
~~~

The parent must expose `<slot name="primary_navigation"></slot>`. Multiple siblings for the same slot are grouped into one template in their sibling order. A slotted component may itself have recursive children. Attributes remain on the component inside the slot wrapper.

## HulmeraEcom Catalog example

Catalog puts common header contributions in `resources/layout/client.json`:

~~~json
[
  {"name":"Homepage","children":[{"name":"AppHeader","children":[
    {"name":"AppHeaderCategories","component":"@@Plugins@@/HulmeraEcom/Catalog/resources/js/Components/Client/AppHeader/Categories.vue","slot_name":"primary_navigation"},
    {"name":"AppHeaderCategoriesMobileNav","component":"@@Plugins@@/HulmeraEcom/Catalog/resources/js/Components/Client/AppHeader/CategoriesMobileNav.vue","slot_name":"mobile_primary_navigation"}
  ]}]}]
~~~

Thus both `client.index` (`client`, `client.index`) and `client.catalog.products` (`client`, `client.catalog`, `client.catalog.products`) receive the desktop and mobile navigation. The product-specific file contains only its page component, header locator/component, listing, and footer rather than duplicating those injections.

## Recommended organization

~~~text
resources/layout/
├── client.json
├── client.index.json
├── client.catalog.json
├── client.catalog.products.json
├── onecol.json
└── onecol.login.json
~~~

Put truly shared contributions in the broadest applicable handle, module-wide contributions in an intermediate handle, and page-only differences in the exact file. Prefer stable descriptive names. Use `slot_name` only when the parent exposes that slot. Multiple enabled plugins can provide the same handle and are merged in configured plugin order.

Run `php artisan oliv:plugins-update --no-interaction` after layout changes, then rebuild Vite/SSR. Generated Vue files under `storage/framework/vue/pages` are compiler output; edit the JSON sources instead.

See [layout generation and middleware](layout-middleware.md) and the [documentation index](../README.md).