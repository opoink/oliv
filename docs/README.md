# OLIV Documentation

OLIV means **Opoink Laravel Inertia Vue**. It combines a Laravel package with installable plugin templates, configuration compilers, Vue page generation, and an Inertia frontend. Its bundled Liv, CMS, Email, and Media plugins supply admin and application features.

These pages describe the inspected package implementation, including unfinished behavior. Source paths are package-relative; runtime paths refer to the Laravel application root. In particular, `src/resources/plugins` contains installation templates that become application `plugins` during installation. Existing application copies can differ.

## Start here

1. Read the [architecture overview](architecture/overview.md) and [requirements](getting-started/requirements.md).
2. Follow [installation](getting-started/installation.md), including its overwrite and migration cautions.
3. Learn [plugin structure](plugins/plugin-structure.md), [compilation](plugins/plugin-update.md), and [configuration](getting-started/configuration.md).
4. Build pages with [layouts](layouts/page-layout.md) and understand [Inertia rendering](helpers/inertia-render.md).
5. Review [known limitations](reference/known-limitations.md) before extending the bundled features.

## Architecture and plugin development

- [Architecture overview](architecture/overview.md)
- [Providers and dependency injection](architecture/service-providers.md)
- [Request lifecycle](architecture/request-lifecycle.md)
- [Plugin structure and registration](plugins/plugin-structure.md)
- [Plugin update and generated files](plugins/plugin-update.md)
- [Plugin configuration merging](plugins/configuration.md)
- [Routes and scheduling](plugins/routes-and-scheduling.md)
- [Models and transactional hooks](plugins/models.md)
- [Custom events](plugins/events.md)

## Installation and commands

- [Requirements](getting-started/requirements.md)
- [Installation workflow](getting-started/installation.md)
- [Environment and configuration](getting-started/configuration.md)
- [`oliv:install`](cli/oliv-install.md)
- [`oliv:plugins-update`](cli/plugins-update.md)
- [Migrations, rollback, and scheduler commands](cli/migrations-and-scheduler.md)

## Layouts and frontend

- [Page layout declarations and merge rules](layouts/page-layout.md)
- [Layout generation API and middleware](layouts/layout-middleware.md)
- [`inertiaRender()`](helpers/inertia-render.md)
- [Inertia and shared props](frontend/inertia.md)
- [Vue pages and global components](frontend/vue.md)
- [Vite build integration](frontend/vite.md)
- [Asset resolution](frontend/asset-resolution.md)
- [CSS loading](frontend/css-loading.md)
- [Theme overrides](frontend/theme-overrides.md)
- [Vite component injection](frontend/component-injection.md)
- [SSR](frontend/ssr.md)
- [Frontend helpers and reusable components](frontend/helpers-and-components.md)

## Bundled admin and plugins

- [Authentication, users, and roles](admin/authentication.md)
- [Admin menus](admin/menus.md)
- [Listings](admin/listings.md)
- [Bookmarks](admin/bookmarks.md)
- [Tabs and admin shell](admin/tabs-and-layouts.md)
- [System configuration and option classes](admin/system-configuration.md)
- [CMS blocks, pages, and editors](bundled-plugins/cms.md)
- [Email templates, SMTP, and queue](bundled-plugins/email.md)
- [Media and image cache](bundled-plugins/media.md)
- [Bundled Liv image library](bundled-plugins/image-library.md)

## Reference

- [PHP helpers, DataObject, Writer, and directories](helpers/helper-functions.md)
- [Directory structure and source coverage](reference/directory-structure.md)
- [Developer conventions](reference/conventions.md)
- [Database tables](reference/database.md)
- [Cache and generated-state lifecycle](reference/caching.md)
- [Known limitations and implementation gaps](reference/known-limitations.md)
- [Documentation map](reference/documentation-map.md)
