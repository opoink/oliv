# CSS loading

Implementation: src/Lib/Plugin/UpdatePlugin.php, functions.php, src/resources/resources/views/app.blade.php, and bundled SCSS resources.

## Area-wide styles

[Plugin compilation](../plugins/plugin-update.md) discovers resources/css/admin.app.scss and client.app.scss in enabled-plugin order. It emits Sass @use statements into the application's matching resources/css files. Each receives a unique namespace, such as adminaapp1 or clientapp1.

These imports use normalized absolute filesystem paths and omit the .scss suffix. Arbitrarily named plugin styles are not discovered automatically; import them from an entry or Vue component.

The [installed shell](inertia.md) loads one area stylesheet through @vite, plus the shared JS entry. Admin styles include Liv font, loader, toast, navigation, listing and login partials; CMS adds its admin editor stylesheet. Client welcome-page styles are imported by the client component.

## Page styles

[inertiaRender()](../helpers/inertia-render.md) can return page_assets.css by recursively traversing a client manifest entry's static imports. Dependencies precede page CSS; URLs are deduplicated. Dynamic imports are excluded.

**Integration status:** no supplied Blade/Vue consumer references page_assets to emit those links. Preparing the prop does not itself load CSS. Vite's normal client import handling and @vite area styles remain distinct mechanisms.

This distinction matters for initial [SSR](ssr.md) HTML and pages whose only styles are in lazy component chunks. Host code would need to consume the prop if it relies on this extra CSS list; the documentation does not claim such wiring already exists.

With public/hot present, the helper skips manifest discovery and sets null unless assets were explicit. Missing/invalid manifests also yield null. A found CSS-free entry yields an empty css array.

## Extension choices

Use conventional area entries for broad plugin styles, and component imports for local dependencies. [Theme source replacement](theme-overrides.md) can affect text modules reached by Vite, but is not a universal static asset override mechanism.

Recompile after adding area entries and rebuild after production style changes. Do not hand-edit generated application SCSS to add durable plugin contributions.

---
[Asset resolution](asset-resolution.md) · [Vite](vite.md) · [Documentation index](../README.md)
