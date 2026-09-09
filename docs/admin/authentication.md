# Authentication, users, and roles

Implementation: src/resources/plugins/Opoink/Liv/Providers/AppServiceProvider.php, Http/Middleware/AdminAuthenticated.php, Http/Controllers/Admin/Login.php, Users/Admins.php, Roles/AdminsRoles.php, Models/AdminUser.php, and functions.php.

## Guard and login

The [Liv provider](../architecture/service-providers.md) configures a session guard named admin with an Eloquent provider named admins. The configured model comes from oliv.auth_admin_user, converting underscores into backslashes.

Login dispatches Plugins_Opoink_Liv_Lib_Facades_Event_Login_authUser with the Request. If a listener has already authenticated the admin guard, it redirects to admin.index. Otherwise it validates email/password and calls guard('admin')->attempt(). Logout flushes the entire session and logs out the guard; its supplied route is GET.

adminauth dispatches its before event with an empty payload, then checks the guard. Unauthenticated AJAX/JSON requests receive 401; other requests redirect to admin.login.

These hooks are [OLIV custom events](../plugins/events.md), so they require the compiled event cache. A listener must establish the guard user; changing arbitrary DataObject flags does not bypass the check.

## Roles

getRolesResource($adminId) actually filters by **admin_user_role_id**, despite the parameter name. It stores a resource-to-resource map in the session under roles_resource. The cache key is not role-specific, and role-edit actions do not invalidate existing sessions.

isRoleAllowed($resource) assumes an authenticated admin. super_admin returns true; a scalar resource checks map membership. The array branch incorrectly indexes the map with the entire array instead of the current element and is not a working "all permissions" API.

Frontend role checking in Liv/resources/js/Lib/common.js has a separate broken array branch: typeof resource == 'array' never identifies JavaScript arrays, and foreach is not forEach. Use neither branch as an established multi-resource contract.

config/roleresource.php declarations are compiled under config('plugins.roleresource') for the role editor. [Menu resource fields](menus.md) control visibility, not server authorization.

## User/role administration

AdminUser uses table admins and extends Authenticatable with HasApiTokens, HasFactory and Notifiable. admin_user_role(): HasOne maps its role ID to AdminsRoles. It does not inherit OLIV's [custom base model](../plugins/models.md).

Users list/add/edit/delete check dedicated resources. Self-edit is explicitly permitted. Updates hash a supplied nonempty password and retain the existing password when omitted. Roles save deletes and recreates resource rows. Role lists and some other legacy lists use cursor pagination.

**Confirmed authorization/serialization limitations:**

- Role add/edit/save/delete actions lack their own resource checks despite adminauth.
- Settings actions likewise have authentication without resource-level checks.
- Self-edit accepts admin_user_role_id, allowing role changes without a separate role-assignment check.
- AdminUser declares no hidden password attribute; several controller responses serialize models directly. Unsetting password in shared middleware does not prove all independent response models are filtered.
- Bookmark visibility save validates an existing row ID but not ownership; see [bookmarks](bookmarks.md).

## Password recovery

Liv uses its own PHPMailer flow with [PHPMAILER environment configuration](../getting-started/configuration.md), distinct from the Email plugin. It validates existence in admins, generates a six-digit rand code, emails it, then saves the code and a two-hour expiry. Reset matches email/code/unexpired time, hashes the password, clears the code, and attempts admin login.

Recovery and user CRUD directly use the bundled AdminUser class/table, even when the guard model is customized. No recovery throttling, attempt counter, or password-strength rule is declared in these supplied routes/controllers.

The [admins migration](../reference/database.md) inserts a known super-admin starter account. Custom models also need the fields consumed by UI and roles: id (or adapted UI), firstname, lastname, email, admin_type, admin_user_role_id, and reset fields.

---
[Installation](../getting-started/installation.md) · [Known limitations](../reference/known-limitations.md) · [Documentation index](../README.md)
