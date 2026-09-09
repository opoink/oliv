# Request lifecycle

Implementation: src/Middleware/HandleInertiaRequests.php, src/Middleware/PageLayout.php, functions.php, and templates under src/resources/routes and src/resources/resources.

1. Laravel loads application configuration and [providers](service-providers.md).
2. Installed routes/web.php requires compiled routes/plugin_web.php, which requires enabled plugin routes.
3. The web group includes HandleInertiaRequests. Protected bundled routes additionally use adminauth.
4. If explicitly registered by the application, PageLayout generates the named route's page before the controller.
5. The controller calls [inertiaRender()](../helpers/inertia-render.md). An existing corresponding generated file replaces its requested component name.
6. Inertia combines shared authentication/menu data and page props. Initial visits use app.blade.php.
7. The [Vue resolver](../frontend/vue.md) locates the page; [SSR](../frontend/ssr.md) uses the same lookup order.

~~~mermaid
flowchart TD
    Route["Plugin route"] --> Auth["adminauth on protected routes"]
    Auth --> Optional["PageLayout if registered"]
    Optional --> Controller
    Controller --> Helper["inertiaRender"]
    Generated["Existing generated Vue page"] --> Helper
    Helper --> Assets["Explicit assets or manifest CSS"]
    Assets --> Response["Inertia response"]
    Response --> Browser["Browser resolver"]
    Response --> SSR["SSR server"]
~~~

This shows conceptual dependencies; actual middleware order also depends on the host. Automatic PageLayout registration is disabled, while [compile-time generation](../layouts/layout-middleware.md) is active.

## Admin versus client

isAdminRoute() compares the first URL segment with getAdminUrl(). It does not inspect route names or authentication. This controls admin shared props, CSS, viewport, fonts, and scripts. A multi-segment admin prefix does not match a single first segment.

adminauth checks the admin guard, returns 401 for AJAX/JSON requests, or redirects to the named login route. It does not enforce individual [role resources](../admin/authentication.md).

There is no automatic client CMS route or client authentication workflow. The bundled client page is a configurable welcome route.

---
[Architecture](overview.md) · [Documentation index](../README.md)
