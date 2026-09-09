# Routes and scheduling

Implementation: src/Lib/Plugin/UpdatePlugin.php, src/resources/routes/web.php, console.php, and bundled plugin routes.

The [compiler](plugin-update.md) writes require_once statements for each enabled plugin's routes/web.php and routes/console.php in registry order. The installed root route templates conditionally require these compiled files and swallow Exception during inclusion. They do not catch every Throwable.

## Web route example

~~~php
use Illuminate\Support\Facades\Route;

Route::middleware('adminauth')
    ->prefix(getAdminUrl())
    ->group(function () {
        Route::get('/example', function () {
            return inertiaRender('Vendor/Plugin/resources/js/Pages/Admin/Index');
        })->name('admin.example');
    });
~~~

This uses the [installed Liv guard](../admin/authentication.md). A client route need not have the admin prefix or middleware. Named routes also establish [generated layout filenames](../layouts/page-layout.md).

The compiler adds no resource authorization, namespace, route prefix, or controller inheritance automatically. API-route collection and output are commented out; there is no active OLIV routes/api.php discovery.

## Scheduling example

~~~php
// plugins/Vendor/Plugin/routes/console.php
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    // Application-owned task.
})->everyThreeMinutes();
~~~

Run [plugin compilation](../cli/plugins-update.md) after adding the file. The root console template loads the compiled include during console boot. OLIV's registered schedule:run subclass adds no active behavior.

The bundled Email plugin has a [sendPending() API](../bundled-plugins/email.md), but no supplied console route schedules it. A host/plugin must wire delivery explicitly.

## Bundled route boundaries

Liv supplies welcome, dashboard, login/logout/reset, admin users, roles, settings, and bookmark visibility routes. CMS supplies admin block/page editing; Email supplies admin template editing; Media exposes a public wildcard image route.

Settings GET and POST share admin.settings.index. Email's listing route is unnamed although controller redirects reference admin.emails. These are [implementation inconsistencies](../reference/known-limitations.md), not recommended conventions.

---
[Commands](../cli/migrations-and-scheduler.md) · [Installation](../getting-started/installation.md) · [Documentation index](../README.md)
