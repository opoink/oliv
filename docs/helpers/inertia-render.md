# inertiaRender()

Implementation: functions.php; delegates to src/resources/plugins/Opoink/Liv/Lib/Inertia.php.

~~~php
function inertiaRender(
    string $component,
    array|\Illuminate\Contracts\Support\Arrayable $props = []
)
~~~

There is no declared return type; the returned value is the result of the bundled Inertia::render wrapper, which delegates to the framework renderer.

## Component selection

Arrayable props are converted to an array. The helper resolves the current Request and calls route()->getName(). The preceding route-existence conditional is empty: **calling it without a matched route can fail**.

For a nonempty route name, dots become spaces, words are capitalized, and spaces are removed. If storage/framework/vue/pages/<ConvertedName>.vue exists, that name replaces the supplied component. This is an existence check, not an ownership check.

~~~php
return inertiaRender('Opoink/Email/resources/js/Pages/Admin/Emails/Index', [
    'propsdata' => $listingData,
]);
~~~

An existing generated page for the current named route takes precedence over that explicit name. [Layout compilation](../layouts/layout-middleware.md) supplies such pages; the helper does not compile layouts.

## Asset discovery

If props already contains the key **page_assets**, its value is preserved exactly, including null. Otherwise:

1. Start with page_assets = null.
2. If public/hot exists, skip manifest processing.
3. Otherwise read public/build/manifest.json if readable.
4. Cache decoded manifest in static variables by path and file modification time.
5. Normalize component backslashes to slashes, trim leading slash, remove a trailing .vue case-insensitively.
6. Choose the first matching manifest key from the candidates below.
7. Recursively gather static-import CSS and assign an object containing css.

~~~text
plugins/<component>.vue
resources/js/Pages/<component>.vue
storage/framework/vue/pages/<component>.vue
~~~

Do not prepend plugins/ to the normal PHP component argument; the helper adds it for its first manifest candidate.

~~~mermaid
flowchart TD
    Input["Component and props"] --> Generated["Select existing route-generated component"]
    Generated --> Explicit{"page_assets key supplied?"}
    Explicit -->|yes| Render["Inertia render"]
    Explicit -->|no| Hot{"public/hot exists?"}
    Hot -->|yes| Null["page_assets = null"]
    Hot -->|no| Manifest["Read/cached client manifest"]
    Manifest --> Entry{"Matching entry?"}
    Entry -->|no| Null
    Entry -->|yes| Traverse["Static imports first, then own CSS"]
    Traverse --> URLs["Deduplicate final /build/ URLs"]
    URLs --> Render
    Null --> Render
~~~

## CSS traversal

A visited set prevents cycles and duplicate chunk visits. Each chunk's imports array is traversed in order **before** its own css array, allowing page CSS to follow dependency CSS. dynamicImports are deliberately ignored.

Only string CSS values with a nonempty slash-trimmed path are collected. URLs are "/build/" plus the path with leading slashes removed. Deduplication uses that final URL, retaining first discovery order.

A matching entry with no CSS yields {"css":[]}. A missing/unreadable/malformed manifest or unmatched entry yields null. Non-array chunks and import/css collections are tolerated. Manifest refresh depends on modification time; replacing content without changing that time may leave a static cached value.

## Explicit assets and limitations

~~~php
return inertiaRender('Example', [
    'page_assets' => ['css' => ['/build/assets/example.css']],
]);
~~~

The helper does not validate explicit assets, discover JavaScript/preloads, use custom Vite build directories, honor an asset-host prefix, or consult an SSR manifest. It does not check APP_ENV; the hot-file presence selects development behavior.

**Consumption gap:** the supplied [Blade shell and Vue templates](../frontend/css-loading.md) do not consume page_assets. The helper prepares props but does not itself emit stylesheet tags. This matters especially for [SSR](../frontend/ssr.md): CSS discovery alone does not prove initial HTML contains those styles.

The bundled Inertia wrapper contains a TODO about PHP theme selection but currently only calls parent::render. Actual overrides occur in [Vite](../frontend/vite.md) through [theme transforms](../frontend/theme-overrides.md).

---
[Asset resolution](../frontend/asset-resolution.md) · [Inertia integration](../frontend/inertia.md) · [Documentation index](../README.md)
