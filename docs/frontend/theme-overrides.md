# Theme overrides

Implementation: src/resources/viteplugins/import.plugin.js and src/resources/vite.config.js.

The transformFileImport() factory returns a Vite plugin named transform-file-import. Its configResolved hook reads **config.env.VITE_OLIV_THEME** per plugin instance, honoring Vite's resolved mode/process environment.

[Installation](../getting-started/installation.md) adds VITE_OLIV_THEME=default. The transform itself has no default fallback; an absent value produces an undefined theme path.

## Resolution flow

For a transformed module ID under application plugins/, backslashes are normalized, then the plugin looks for the same suffix under theme/<theme>/.

~~~text
plugins/Opoink/Liv/resources/js/Pages/Admin/Users/Login.vue
theme/default/Opoink/Liv/resources/js/Pages/Admin/Users/Login.vue
~~~

~~~mermaid
flowchart TD
    Module["Vite module ID"] --> Match{"Under plugins/?"}
    Match -->|no| Original["Return original source"]
    Match -->|yes| Theme["theme / resolved theme / matching suffix"]
    Theme --> Exists{"File exists and nonempty?"}
    Exists -->|no| Original
    Exists -->|yes| Replace["Read UTF-8 and replace source"]
    Replace --> Watch["Watch existing override file"]
~~~

Existing overrides are added with addWatchFile. Read/parsing exceptions are logged to console and fall back to original source. Empty files also fall back rather than blanking the module.

## Precedence and scope

There is one selected theme and one corresponding override candidate. No parent-theme chain or per-plugin theme order exists. The returned code uses the original module ID and map: null, so relative imports resolve from the plugin path, not the theme directory.

The transform is registered as pre and has no dev/build-only apply filter; it participates in both development and production processing. Restart/rebuild when changing environment selection.

It reads source as UTF-8, without extension filtering. Vue/JS text substitution is the direct implemented behavior. CSS/assets depend on whether and how Vite sends their modules through transform; this does **not** establish byte-level replacement of binary image files. Public-file requests and PHP filesystem reads bypass it.

IDs with query suffixes are not stripped before constructing the theme path. New override files that did not exist at transform time are not added to the watch list. No source map is generated.

The old README broadly promises image and stylesheet overrides; the actual hook has these narrower mechanics. The PHP Inertia wrapper's theme TODO is inactive.

---
[Assets](asset-resolution.md) · [Vite](vite.md) · [Documentation index](../README.md)
