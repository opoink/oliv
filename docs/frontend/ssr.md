# Server-side rendering

Implementation: src/resources/resources/js/ssr.js, src/resources/config/inertia.php, src/resources/ecosystem.config.js, and installer build:ssr script.

## Workflow

After [installation](../getting-started/installation.md) and plugin compilation:

~~~bash
npm run build:ssr
php artisan inertia:start-ssr
~~~

build:ssr runs the client build and then vite build --ssr. ssr.js uses createServer, createInertiaApp, createSSRApp, renderToString, the [same page resolver](vue.md), Filters, and global-component registrations.

The server port is process.env.VITE_INERTIA_SSR_PORT or 13714. PHP's Inertia config enables SSR and points to http://127.0.0.1:<port>. This is a configuration example, not a verified running service address.

A supplied PM2 ecosystem file starts ./bootstrap/ssr/ssr.js, uses watch: "true", and sets port 13714. Its CommonJS module.exports syntax may need host consideration when package.json declares ESM; direct PM2 execution was not validated.

## Boundaries

Client and SSR builds must include the same generated pages and globals. A request-time generated file cannot add a module to an already built bundle.

[inertiaRender()](../helpers/inertia-render.md) consults the **client** manifest, never an SSR manifest. Its page_assets prop has no consumer in the supplied templates, so it does not automatically provide SSR stylesheet tags.

Some bundled UI libraries access window/document, globals or browser-only editor plugins. The shared entry supplies no universal SSR guard for those dependencies. OwlCarousel and CMS import third-party editor/browser modules at module scope; those dependencies need independent SSR validation. CMS also assumes global TinyMCE during browser lifecycle initialization. That onBeforeMount callback itself is not a server-render hook. Browser and SSR compatibility of every editor/widget is not established by the existence of ssr.js.

---
[Inertia](inertia.md) · [Vite](vite.md) · [Documentation index](../README.md)
