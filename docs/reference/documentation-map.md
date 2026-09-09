# Documentation map

OLIV's major systems connect through installed templates and generated artifacts.

~~~mermaid
flowchart TD
    Install["Installation"] --> Registry["Plugin registry"]
    Install --> Shell["Blade and Vue entry points"]
    Registry --> Update["Plugin compilation"]
    Update --> Config["PHP config and providers"]
    Update --> Routes["Web and console includes"]
    Update --> Events["Custom event cache"]
    Update --> Settings["System settings metadata"]
    Update --> Layout["Generated Vue pages"]
    Update --> Globals["Global components and page globs"]
    Update --> Styles["Area SCSS"]
    Routes --> Controller["Controllers"]
    Controller --> Render["inertiaRender"]
    Layout --> Render
    Render --> Props["Inertia props and page_assets"]
    Config --> Auth["Admin guard and menus"]
    Auth --> Props
    Globals --> Vite
    Styles --> Vite
    Layout --> Vite
    Vite --> Theme["Theme source transform"]
    Vite --> Injection["Comment injection"]
    Vite --> Client["Client and SSR bundles"]
    Shell --> Client
    Props --> Client
    Settings --> Admin["Settings UI"]
    Events --> Models["Model and auth hooks"]
    Models --> CMS["CMS and email persistence"]
~~~

Begin with [architecture](../architecture/overview.md) and [installation](../getting-started/installation.md), then follow [plugin compilation](../plugins/plugin-update.md).

For page composition, read [layout schema](../layouts/page-layout.md), [generation timing](../layouts/layout-middleware.md), and [inertiaRender](../helpers/inertia-render.md). Its CSS output's [missing consumer](../frontend/css-loading.md) is a deliberate limitation shown by documentation, not an implied working link in the diagram.

For frontend extension, compare [theme replacement](../frontend/theme-overrides.md), [comment injection](../frontend/component-injection.md), and [global components](../frontend/vue.md).

For admin development, connect [authentication](../admin/authentication.md), [menus](../admin/menus.md), [listings](../admin/listings.md), [bookmarks](../admin/bookmarks.md), and [settings](../admin/system-configuration.md).

The [model lifecycle](../plugins/models.md) uses [custom events](../plugins/events.md), whose registration depends on [cache state](caching.md). Bundled [CMS](../bundled-plugins/cms.md), [Email](../bundled-plugins/email.md) and [Media](../bundled-plugins/media.md) extend different parts of this infrastructure.

---
[Complete index](../README.md) · [Source coverage](directory-structure.md)
