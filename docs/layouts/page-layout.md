# Page layout declarations and merge rules

Implementation: Opoink\Oliv\Lib\Plugin\Layout in src/Lib/Plugin/Layout.php. Bundled example: src/resources/plugins/Opoink/Liv/resources/layout/admin.index.json.

Layouts compose a complete Vue page from contributions in enabled-plugin order. After [installation](../getting-started/installation.md), put each definition at:

~~~text
plugins/Vendor/Plugin/resources/layout/<route-name>.json
~~~

For route admin.index, the generated target is storage/framework/vue/pages/AdminIndex.vue. Dot-separated words are capitalized and joined; hyphens and underscores are not removed by that conversion. Avoid names that collapse to the same filename.

## Array schema

~~~json
[
  {
    "name": "Shell",
    "component": "@@Plugins@@/Opoink/Liv/resources/js/Layouts/Admin/Default.vue",
    "attr": {"class": "example-page"},
    "children": [
      {
        "name": "Content",
        "component": "@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Pages/AdminIndex.vue"
      }
    ]
  }
]
~~~

This is the entire file: no page-name, vendor, or components wrapper.

| Key | Contract |
| --- | --- |
| name | Nonempty string; logical identity across the entire page |
| component | Nonempty module specifier for a default export; may be omitted for a locator |
| import | Legacy single-binding import; only used if component key is absent |
| attr | Optional object/array of attribute key/value pairs |
| children | Recursive node list or legacy keyed object |
| remove | Only literal true removes; a later present non-true value re-enables |

Children can have children without a fixed schema depth limit. Wrapper components need a default slot to render descendants.

## Merging and targeting

All contributions are collected before resolving nodes. Duplicate names mean **one node**, even across depths. To render the same component twice, give its instances distinct names.

- The first valid component/export definition wins. Repeated identical definitions are harmless; conflicts warn and keep the first.
- The first nested parent assignment wins. Conflicting parents and cyclic edges warn and skip that contribution.
- New roots and siblings retain first-contribution order. There is no layout sort_order or before/after key.
- A top-level locator can address an existing nested node without promoting it to root.
- Later attr values replace individual earlier values via array_replace.
- remove: true hides a node and its entire subtree. A later remove: false can restore it.
- A locator still lacking a component after collection is skipped with its subtree.

For example, a later plugin can extend the Content node by name:

~~~json
[
  {
    "name": "Content",
    "attr": {"class": "updated-content"},
    "children": [
      {
        "name": "Extra",
        "component": "Plugins/Vendor/Plugin/resources/js/Components/Extra.vue"
      }
    ]
  }
]
~~~

This is a schema example; supply the referenced component and a default slot in Content. Adding a child does not insert a slot into an existing component.

## Legacy keyed format

~~~json
{
  "Head": {
    "import": "import { Head } from '@inertiajs/vue3'",
    "attr": {"title": "Dashboard"}
  },
  "Body": {
    "import": "import Body from '@@Plugins@@/Opoink/Liv/resources/js/Components/Admin/Pages/AdminIndex.vue'"
  }
}
~~~

String object keys supply names when name is absent/null. Default imports or a single named import, optionally aliased, are recognized. Multiple bindings, namespace imports, or arbitrary JavaScript are rejected. The explicit component key takes precedence even if invalid. A direct export key is not consumed; named exports use the legacy import form.

Legacy local binding names are replaced by generated identifiers, so attribute expressions must not depend on them.

## Paths, imports, and attributes

Backslashes in module paths become forward slashes. **The code that normalizes @@Plugins@@/ into Plugins/ is commented out.** Those two spellings remain distinct for conflict detection and import deduplication, even though [Vite aliases](../frontend/asset-resolution.md) point them at the same directory.

Imports are deduplicated by generated identifier: OlivComponent_ plus the first 16 SHA-256 hex characters of path + "#" + export. Default and named exports are distinct.

Attributes require a string key without whitespace, quotes, angle brackets, slash, or equals. Values must be scalar or null. Values are cast to strings and HTML-escaped; false/null become empty strings, true becomes "1". Vue directives such as :title preserve expression semantics, so definitions are trusted source code. They are not a safe user-authored template language.

Leaves are self-closing; wrappers recursively contain rendered children. Unrecognized node keys are ignored.

## Invalid or missing input

Malformed JSON, invalid nodes, attributes, children, paths, conflicts, cycles and unresolved locators generate Laravel warnings. Valid contributions can still compile. Missing local Vue modules are left for Vite to reject; PHP does not check module existence.

If source files exist but none decode to arrays, the previous generated page is retained. A valid empty array can generate an empty page. When all sources disappear, only an owned generated target is deleted.

The system supports both admin and client named pages. Request timing and API details are in [layout generation](layout-middleware.md). Before/after comment injection belongs to the [separate Vite system](../frontend/component-injection.md).

---
[Rendering helper](../helpers/inertia-render.md) · [Documentation index](../README.md)
