# Architecture overview

OLIV has two delivery layers: Composer-loaded PHP under **src**, and application templates under **src/resources**. The [installer](../getting-started/installation.md) copies the latter into Laravel. Editing a package template does not update an existing application copy automatically.

## Execution layers

~~~mermaid
flowchart TD
    Composer["Composer autoload and package provider"] --> Laravel
    Installer["oliv:install"] --> App["Application plugins and frontend templates"]
    App --> Registry["plugins/config.json"]
    Registry --> Compiler["oliv:plugins-update"]
    Compiler --> PHP["Config, providers, route includes, event cache"]
    Compiler --> Vue["Page glob, globals, SCSS, generated Vue layouts"]
    PHP --> Laravel["Laravel request"]
    Laravel --> Render["inertiaRender"]
    Vue --> Vite["Vite client and SSR builds"]
    Render --> Inertia["Inertia response"]
    Vite --> Inertia
~~~

The [plugin compiler](../plugins/plugin-update.md) converts an ordered plugin list into PHP and JavaScript artifacts. It is not a downloader, dependency solver, or versioned plugin installer. Metadata versions are collected without upgrade comparisons.

[Named-route layouts](../layouts/page-layout.md) generate complete Vue pages. Separately, [Vite injection](../frontend/component-injection.md) inserts components into source comments. Their declarations and merge rules differ.

The [render helper](../helpers/inertia-render.md) can select an existing generated page and discover production CSS. Browser and SSR entry points resolve page names against compiled globs. [Theme substitution](../frontend/theme-overrides.md) occurs in Vite's transform hook.

## Bundled application layer

| Plugin | Responsibilities |
| --- | --- |
| Opoink_Liv | Admin guard, users/roles, menus, settings, listings/bookmarks, custom events, base model, UI helpers |
| Opoink_Cms | Block/page storage, visual editors, separate Vue component writer |
| Opoink_Email | Editable templates, PHPMailer delivery, database queue |
| Opoink_Media | Image URL helpers, resizing, public generated images |

Liv is an operational dependency of the other bundled plugins and several global helpers. No plugin dependency graph enforces this.

The package provider activates only Inertia middleware; [PageLayout registration is commented out](../layouts/layout-middleware.md). Compilation still generates layouts before builds. Menu visibility and frontend role checks are not backend authorization; see [authentication](../admin/authentication.md).

Implementation: composer.json, functions.php, src/Providers/AppServiceProvider.php, src/Lib/Plugin/UpdatePlugin.php, src/resources/plugins.

---
[Documentation index](../README.md) · [System map](../reference/documentation-map.md)
