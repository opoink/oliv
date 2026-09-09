# Inertia and shared props

Implementation: src/Middleware/HandleInertiaRequests.php, src/resources/resources/views/app.blade.php, src/resources/resources/js/app.js and ssr.js, and bundled Liv/Lib/Inertia.php.

[Installation](../getting-started/installation.md) supplies the app root view and entry points. The package provider appends HandleInertiaRequests to web. rootView is app; version(Request): ?string delegates to the framework.

## Shared data

share(Request): array merges parent props with:

~~~text
auth:
  admin: null or admin attribute array
  client: request()->user()
admin_url: configured prefix (admin routes only)
adminmenu: config('adminmenus') (admin routes only)
~~~

The admin user is obtained from the admin guard. Its password attribute is unset before dispatching the custom Opoink_Oliv_Middleware_HandleInertiaRequests event with adminUser. Throwable from this dispatch is swallowed. Then getAttributes() supplies the shared array.

If listeners have not supplied roles_resource, super_admin receives "*"; other admins receive the [session-cached resource map](../admin/authentication.md). This is direct attribute extraction, not a dedicated API resource; do not assume hidden model attributes are automatically filtered by getAttributes().

The admin-route distinction uses the URL-prefix [helper](../helpers/helper-functions.md). Client auth is shared independently.

## Rendering and root shell

[inertiaRender()](../helpers/inertia-render.md) selects generated pages and prepares asset props. The Liv Inertia subclass only delegates to the framework; its PHP theme-resolution TODO is unimplemented.

The root view uses @vite, @inertiaHead and @inertia. Admin visits load Font Awesome, Bootstrap CSS/JS from a CDN, Google fonts and /assets/tinymce/tinymce.min.js, plus admin SCSS. Client visits load client SCSS. Both use resources/js/app.js.

Ziggy's @routes output is only a comment; no route map is exposed by this shell. Some older frontend helpers still assume a global route() function, an [integration gap](../reference/known-limitations.md).

The shell does not render page_assets stylesheet links; see [CSS loading](css-loading.md).

---
[Vue resolver](vue.md) · [SSR](ssr.md) · [Documentation index](../README.md)
