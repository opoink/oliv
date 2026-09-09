# Directory structure and source coverage

Package runtime code lives under src; installation templates live under src/resources. [Installation](../getting-started/installation.md) copies those templates to the application. Existing installed copies can differ.

~~~text
composer.json
functions.php
src/
  Providers/
  Middleware/
  Console/Commands/
  Console/Scheduling/
  Lib/Plugin/
  Facades/
  resources/
    app/Http/Controllers/
    config/
    routes/
    resources/js/
    resources/views/
    plugins/Opoink/{Liv,Cms,Email,Media}/
    public/
    viteplugins/
    vite.config.js
    ecosystem.config.js
    jsconfig.json
docs/
~~~

No separate package-root routes, config, database, resources or viteplugins directories exist here. Relevant files are nested under src/resources/plugins or src/resources. The provider's missing src/config publishing source is a [known limitation](known-limitations.md).

## Runtime source map

| Source | Documentation |
| --- | --- |
| composer.json and old README.md | [Requirements](../getting-started/requirements.md), [architecture](../architecture/overview.md), [README discrepancies](known-limitations.md) |
| functions.php | [Helpers](../helpers/helper-functions.md), [inertiaRender](../helpers/inertia-render.md) |
| src/Providers and src/Facades | [Providers and DI](../architecture/service-providers.md) |
| src/Middleware/HandleInertiaRequests.php | [Inertia](../frontend/inertia.md) |
| src/Middleware/PageLayout.php and src/Lib/Plugin/Layout.php | [Layout schema](../layouts/page-layout.md), [generation API](../layouts/layout-middleware.md) |
| src/Console/Commands/Install.php | [Installer](../cli/oliv-install.md) |
| src/Console/Commands/PluginsUpdate.php and src/Lib/Plugin/UpdatePlugin.php | [Command](../cli/plugins-update.md), [compiler](../plugins/plugin-update.md) |
| MigrateCommand.php, RollbackCommand.php, Console/Scheduling/ScheduleRunCommand.php | [Migration/scheduler commands](../cli/migrations-and-scheduler.md) |
| src/Lib/Plugin/MergeAdminMenu.php | [Menus](../admin/menus.md) |
| src/Lib/Plugin/MergeSystemConfig.php | [Settings](../admin/system-configuration.md) |
| src/Lib/DataObject.php, Dirmanager.php, Writer.php | [Helper APIs](../helpers/helper-functions.md) |

## Installation templates

Paths in this table are beneath src/resources.

| Source | Documentation |
| --- | --- |
| app/Http/Controllers/Controller.php | [Installer overwrite](../cli/oliv-install.md); extends Laravel routing controller, adds AuthorizesRequests/ValidatesRequests |
| config/oliv.php, inertia.php | [Configuration](../getting-started/configuration.md) |
| routes/web.php, console.php | [Routes and schedules](../plugins/routes-and-scheduling.md) |
| vite.config.js, viteplugins | [Vite](../frontend/vite.md), [themes](../frontend/theme-overrides.md), [injection](../frontend/component-injection.md) |
| ecosystem.config.js | [SSR](../frontend/ssr.md) |
| jsconfig.json | Editor alias @@Plugins@@/* to plugins/*; includes all files |
| resources/js/app.js, ssr.js | [Vue](../frontend/vue.md), [SSR](../frontend/ssr.md) |
| resources/js/common.js, Plugins/filters.js | [Frontend helpers](../frontend/helpers-and-components.md) |
| resources/views/app.blade.php | [Shell](../frontend/inertia.md), [CSS](../frontend/css-loading.md) |
| plugins/config.sample.json and plugin config.json | [Registration/metadata](../plugins/plugin-structure.md) |

## Bundled plugins

Paths below are beneath src/resources/plugins/Opoink.

| Source group | Documentation |
| --- | --- |
| Liv/Providers, Http/Middleware, Admin/Login.php, Users, Roles, auth models | [Authentication and administration](../admin/authentication.md) |
| Liv/Http/Controllers/Admin/Settings, etc/admin/system.php, Lib/SystemConfig.php, Models/SystemConfig.php, Lib/Option | [Settings and options](../admin/system-configuration.md) |
| Liv/Http/Controllers/Admin/AdminListing, Models/ListingBookmark.php | [Bookmarks](../admin/bookmarks.md) |
| Liv/Lib/AdminListing.php, QryFilter.php | [Listings](../admin/listings.md) |
| Liv/Lib/Event.php and Models/Model.php | [Events](../plugins/events.md), [model hooks](../plugins/models.md) |
| Liv/Lib/Inertia.php | [Inertia wrapper](../frontend/inertia.md) |
| Liv/Lib/Facades | [Facade targets](../architecture/service-providers.md) |
| Liv/Lib/Gumlet | [Separate image library](../bundled-plugins/image-library.md) |
| Liv/config and resources/layout | [Menus](../admin/menus.md), [roles](../admin/authentication.md), [layouts](../layouts/page-layout.md) |
| Liv/resources/js/Layouts, States, Lib, Components, GlobalComponents | [Helpers/widgets](../frontend/helpers-and-components.md), [tabs](../admin/tabs-and-layouts.md), [settings](../admin/system-configuration.md) |
| Liv/resources/js/Pages | [Admin workflows](../admin/authentication.md), [Vue conventions](../frontend/vue.md) |
| Liv/Http/Controllers/Client/Index.php | Welcome route returns OLIV Composer InstalledVersions value; controlled by [configuration](../getting-started/configuration.md) |
| Liv/Http/Controllers/Admin/Index.php | Dashboard via [render helper](../helpers/inertia-render.md) |
| Liv/resources/css and resources/views/email | [Styles](../frontend/css-loading.md), [password recovery](../admin/authentication.md) |
| Cms controllers/models/Lib/resources/config/routes | [CMS](../bundled-plugins/cms.md) |
| Email controllers/models/Lib/resources/etc/config/routes | [Email](../bundled-plugins/email.md), [bookmark schema](../admin/bookmarks.md) |
| Media controllers/Lib/resources/routes | [Media](../bundled-plugins/media.md) |
| All migrations | [Database](database.md) |
| All route files | [Route loading](../plugins/routes-and-scheduling.md) |

## Static and third-party material

public includes Font Awesome 6.4.2 CSS/JS/metadata/SCSS/LESS/SVG/fonts, TinyMCE under assets/tinymce, and public images. Liv bundles OwlCarousel resources and branding; CMS includes editor styles and a three-column project JSON.

These are installer payloads/UI dependencies, not distinct OLIV extension APIs. Their integrations are covered under [widgets](../frontend/helpers-and-components.md), [CMS](../bundled-plugins/cms.md), and [assets](../frontend/asset-resolution.md). Individual icons, skins and minified upstream functions are not presented as OLIV features.

LICENSE is MIT. Git metadata and .gitignore are repository support files. No package test suite, standalone factories or seeders were found; initial records are inserted by migrations.

---
[Documentation map](documentation-map.md) · [Complete index](../README.md)
