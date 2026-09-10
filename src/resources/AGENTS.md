# AGENTS.md

# OLIV Agent Instructions

This repository uses **OLIV (Opoink Laravel Inertia Vue)** conventions.

These instructions apply to **all coding agents, AI assistants, sub-agents, and automated code-generation tools** working in this repository.

Before implementing any task that touches OLIV, plugins, Laravel backend code, Inertia pages, Vue components, admin pages, frontend pages, listings, configuration, SCSS, layouts, or plugin architecture, the agent **must inspect and follow the existing OLIV implementation and documentation**.

Primary OLIV documentation:

- https://github.com/opoink/oliv/blob/main/docs/README.md
- `/vendor/opoink/oliv/docs/README.md` when the package documentation is available locally.

The local installed OLIV implementation is the primary source of truth for the version used by the application. The GitHub documentation is a supporting reference.

If these instructions conflict with a task-specific user prompt, the **explicit task-specific prompt takes precedence**.

---

## 1. Core Principle: Follow OLIV Before Inventing Anything

Do not create a custom implementation when OLIV already provides a convention, helper, component, service, structure, or established pattern for the same purpose.

Before implementing a feature:

1. Inspect relevant OLIV documentation.
2. Inspect similar implementations under existing plugins.
3. Reuse the OLIV pattern.
4. Extend the pattern only when necessary.
5. Do not replace an existing OLIV mechanism with a custom abstraction without explicit instruction.

When there is uncertainty, prefer an existing implementation in:

```text
/plugins/Opoink
```

as a **read-only implementation reference**.

Do not modify it unless the prompt explicitly requires that directory to be changed.

---

# 2. Protected Directories

By default, **DO NOT modify**:

```text
/plugins/Opoink
/vendor
```

This includes:

```text
/vendor/opoink/oliv
```

These directories may be inspected freely for documentation, examples, architecture, and implementation references.

They may only be modified when the prompt **explicitly requires** the modification.

Do not make a change inside these directories merely because it is easier than implementing the change in the application/plugin that owns the feature.

---

# 3. Do Not Minify Source Files

Do **not** minify any source file unless minification is explicitly required by the prompt or is an unavoidable output of an existing production build process.

This applies to, among others:

- JavaScript
- Vue
- TypeScript
- PHP
- CSS
- SCSS
- JSON
- HTML

Keep source files readable, formatted, and maintainable.

Do not manually replace readable source code with compressed/minified code.

---

# 4. OLIV Plugin Conventions

Follow the plugin conventions documented by OLIV and confirmed by the installed implementation.

Typical plugin structure:

```text
plugins/
└── Vendor/
    └── Plugin/
        ├── config/
        ├── etc/
        ├── Http/
        │   └── Controllers/
        │       ├── Admin/
        │       └── Client/
        ├── Lib/
        ├── migrations/
        ├── Models/
        ├── Providers/
        └── resources/
            ├── css/
            ├── js/
            └── layout/
```

Do not assume every plugin needs every directory.

Only add structures required by the feature.

### Plugin naming

OLIV plugin identifiers follow the:

```text
Vendor_Plugin
```

convention and map to:

```text
plugins/Vendor/Plugin
```

### Namespace

Application plugins use the:

```php
Plugins\Vendor\Plugin
```

namespace structure.

### Providers

Providers intended for OLIV automatic provider discovery must follow OLIV's expected provider location and structure.

Do not invent alternate provider discovery mechanisms.

### Migrations

Plugin migrations must follow OLIV's plugin migration location and existing conventions.

Inspect OLIV documentation before changing migration discovery or lifecycle behavior.

---

# 5. Models

For plugin models, follow the OLIV/Liv model convention.

Unless a specific implementation requires otherwise, plugin models should extend:

```php
\Plugins\Opoink\Liv\Models\Model
```

Example:

```php
namespace Plugins\Vendor\Plugin\Models;

class Example extends \Plugins\Opoink\Liv\Models\Model
{
}
```

Do not automatically use:

```php
Illuminate\Database\Eloquent\Model
```

for OLIV plugin models when the Liv model is the established pattern.

Inspect existing OLIV models when lifecycle hooks, custom events, transactions, or other Liv behavior may matter.

---

# 6. Option Classes

When a field has predefined/selectable values, statuses, types, categories, labels, modes, or similar enumerated choices, use a dedicated OLIV-style option class.

Preferred location:

```text
Lib/Option/
```

Example:

```text
Lib/Option/StatusOption.php
Lib/Option/TypeOption.php
Lib/Option/AiProviderOption.php
```

The option class must be the **single source of truth** for those values.

Do not duplicate the same status/type values in:

- controllers
- Vue components
- validation rules
- listings
- services
- templates

unless technically required, and then derive them from the option source whenever possible.

Do not hardcode option labels independently in multiple places.

---

# 7. Admin System Configuration

For OLIV admin settings, use the existing OLIV system configuration convention.

The expected location is:

```text
etc/admin/system.php
```

Do not invent a separate settings framework when OLIV's system configuration is appropriate.

Inspect existing OLIV system configuration implementations and option classes before adding a new configuration field.

---

# 8. Admin Styling

For admin interfaces:

**Always use Bootstrap 5 or later conventions as the primary styling system.**

Prefer existing Bootstrap utilities and components before creating custom CSS.

Examples:

```html
<div class="row">
    <div class="col-md-6">
        ...
    </div>
</div>
```

```html
<button class="btn btn-primary">
    Save
</button>
```

```html
<div class="alert alert-danger">
    ...
</div>
```

Only create custom component-level styles when Bootstrap is insufficient.

If custom component-specific styling is genuinely needed, scoped SCSS may be used:

```vue
<style scoped lang="scss">
...
</style>
```

Do not create unnecessary custom admin design systems.

Do not replace Bootstrap components with custom implementations without a task-specific reason.

---

# 9. Frontend SCSS Convention

Frontend page styling must be organized so page dependencies can compile into an efficient page-level CSS output.

## Required rule

Each frontend page should have a **page-level SCSS file**.

Example:

```text
resources/js/Pages/Client/Home.vue
resources/css/pages/home.scss
```

or the equivalent plugin-local organization already established in that plugin.

The page-level SCSS should then include/use the SCSS required by the components used by that page.

Example:

```scss
@use "../components/app_header";
@use "../components/hero";
@use "../components/features";
@use "../components/app_footer";
```

The intent is:

```text
Page
  ↓
Page SCSS
  ↓
Component SCSS dependencies
  ↓
One page CSS output during build
```

Do not scatter page CSS imports in a way that unnecessarily creates many independent CSS chunks when the established page-level aggregation pattern can be used.

### Component SCSS

Reusable frontend components should have their own SCSS when they contain meaningful component-specific styles.

Example:

```text
AppHeader.vue
AppHeader.scss

AppFooter.vue
AppFooter.scss
```

Then the page SCSS can aggregate the relevant component SCSS.

### Area-wide SCSS

Respect OLIV's special area-wide files:

```text
resources/css/admin.app.scss
resources/css/client.app.scss
```

OLIV discovers these conventional filenames for area-wide styles.

Do not rename or duplicate this mechanism with arbitrary global stylesheet entry names unless explicitly required.

---

# 10. Vue Component Organization

Follow the existing plugin organization.

Prefer reusable Vue components under a dedicated components directory such as:

```text
resources/js/Components/Admin/
resources/js/Components/Client/
```

Page components belong under:

```text
resources/js/Pages/Admin/
resources/js/Pages/Client/
```

Do not place reusable components inside a page directory merely for convenience when they belong in the plugin's reusable Components structure.

Before creating a new organizational pattern, inspect similar OLIV plugins.

---

# 11. Admin Listings

For admin listings, follow the **OLIV listing/bookmark pattern**.

Do not create a custom listing implementation when OLIV's listing infrastructure can handle the feature.

Relevant OLIV concepts include:

```php
\Plugins\Opoink\Liv\Lib\AdminListing
```

and bookmark definitions such as:

```text
Lib/Bookmarks/*.json
```

Use an existing OLIV listing implementation as the primary reference.

The listing should follow OLIV's expected:

- bookmark configuration
- columns
- filters
- sorting
- pagination
- visible-column behavior
- listing namespace
- action slot
- Inertia props structure

Do not implement independent pagination/filter/sorting state in Vue when the OLIV listing system is suitable.

### Typical backend pattern

Follow the installed OLIV implementation, generally along the lines of:

```php
$listing = app(\Plugins\Opoink\Liv\Lib\AdminListing::class);

$result = $listing
    ->setTargetDefaultBookmark('plugins/Vendor/Plugin/Lib/Bookmarks/items.json')
    ->setNamespace('vendor_plugin_items_listing')
    ->getPaginator(\Plugins\Vendor\Plugin\Models\Item::class);
```

The exact implementation must be based on the current installed OLIV version and an existing working listing.

Do not blindly copy this example if the actual nearby implementation differs.

---

# 12. Admin Listing Action Button

For the action column in OLIV admin listings, use the established Bootstrap dropdown action pattern.

Use this pattern unless the task specifically requires a different interaction:

```vue
<template #item="{ item }">
    <button
        type="button"
        class="btn btn-outline-secondary btn-sm dropdown-toggle"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        Action
    </button>

    <ul class="dropdown-menu">
        <li>
            <Link
                class="dropdown-item"
                :href="getAdminUrl('/url/path/to' + item.id)"
                v-bind:data-id="item.id"
            >
                <i class="fa-solid fa-pencil"></i>
                <span>sample item button</span>
            </Link>
        </li>
    </ul>
</template>
```

Adapt:

- URL
- icon
- label
- permission checks
- action behavior

to the actual feature.

Do not replace this with a row of unrelated buttons unless the prompt explicitly asks for that layout.

When multiple actions exist, keep them grouped inside the dropdown where appropriate.

For destructive actions such as delete:

- keep the action inside the dropdown,
- use an appropriate confirmation modal,
- do not execute destructive operations directly from the initial click without confirmation unless the prompt explicitly says otherwise.

Follow the exact slot name required by the OLIV listing component being used. Inspect the existing working listing if uncertain.

---

# 13. Admin View / Add / Edit Pages

Do **not re-invent OLIV admin view, add, or edit pages**.

Before implementing one, inspect this known-good reference:

```text
/plugins/Opoink/Email/resources/js/Pages/Admin/Emails/AddEdit.vue
```

This file is a primary UI/structure reference for OLIV admin add/edit pages.

Use its established patterns where applicable for:

- admin shell/layout
- tabs
- page structure
- form layout
- buttons
- submission flow
- FormData
- Bootstrap classes
- validation/error presentation
- Inertia integration

Other existing OLIV admin pages may also be inspected when they more closely match the requested feature.

### Add and Edit

When appropriate, prefer one reusable Add/Edit page rather than duplicating nearly identical Vue components.

A common convention is:

```text
No ID / no existing entity → Add
Existing ID/entity → Edit
```

Follow the feature's controller and route conventions rather than inventing arbitrary detection logic.

### View pages

Use existing OLIV admin view/tabs/layout conventions.

Do not create a standalone custom admin layout if the current OLIV admin shell already supports the required page.

---

# 14. FormData Convention

For OLIV admin forms, use the existing OLIV `FormData` helper convention.

Import:

```js
import { FormData as _FormData } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/form.data';
```

Create the reactive form object using:

```js
const formData = reactive(new _FormData());
```

A known-good reference is:

```text
/plugins/Opoink/Email/resources/js/Pages/Admin/Emails/AddEdit.vue
```

Before implementing form submission, inspect that file and follow the same OLIV conventions where applicable.

Do not replace this with:

```js
const form = reactive({})
```

or a custom form abstraction when the page should follow the OLIV FormData pattern.

Do not introduce another form library solely for convenience unless the prompt explicitly requires it.

---

# 15. Inertia Rendering

When rendering OLIV Inertia pages, use the OLIV mechanisms already established in the installed package.

Inspect:

```text
/vendor/opoink/oliv/functions.php
```

and the OLIV documentation for:

```php
inertiaRender()
```

before creating a custom Inertia response convention.

Be aware that OLIV's implementation may participate in:

- page resolution
- page assets
- Vite manifest lookup
- CSS collection
- static import traversal
- SSR behavior
- plugin component resolution

Do not bypass these features with a raw custom rendering approach without a specific reason.

---

# 16. Layouts and Component Injection

OLIV includes page layout and component injection mechanisms.

Before manually hardcoding shared page structures, inspect:

```text
/vendor/opoink/oliv/docs/layouts/
```

and the relevant implementation, including:

```text
Opoink\Oliv\Middleware\PageLayout
Opoink\Oliv\Lib\Plugin\Layout
```

When a feature is intended to be injectable/extensible by other plugins, use OLIV's layout/component system rather than tightly coupling all components directly into one page.

Do not invent a parallel component-injection architecture.

When editing an existing layout declaration:

- preserve its existing schema,
- preserve component ordering unless the prompt changes it,
- respect parent/child relationships,
- inspect the current layout merge rules,
- do not assume "last plugin wins."

---

# 17. Vite and Theme Overrides

OLIV has its own Vite/plugin resource resolution and theme override behavior.

Before modifying:

- plugin import aliases,
- plugin Vue resolution,
- component injection,
- theme overrides,
- asset paths,
- Vite plugin behavior,

inspect the current OLIV documentation and implementation.

Do not create another alias system or theme override mechanism if OLIV already provides one.

Do not modify OLIV's Vite implementation inside `/vendor` unless explicitly required.

---

# 18. FontAwesome

When the project uses the Vue FontAwesome integration, follow the existing component-import pattern rather than assuming raw `<i>` tags.

Example:

```js
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPencilAlt } from '@fortawesome/free-solid-svg-icons';
```

Template:

```vue
<FontAwesomeIcon :icon="faPencilAlt" />
```

However, some existing OLIV admin patterns may use Font Awesome CSS classes.

When modifying an existing page, preserve the icon system already used by that area unless the prompt requires migration.

Do not mix icon systems unnecessarily within the same component.

---

# 19. Existing Code Is the Best Implementation Example

When asked to build a feature that already exists in a similar form elsewhere, inspect the closest existing implementation first.

Examples of preferred references:

### Admin add/edit

```text
/plugins/Opoink/Email/resources/js/Pages/Admin/Emails/AddEdit.vue
```

### Listings

Inspect existing OLIV bookmark-backed listings, especially the Email plugin and current OLIV documentation.

### Editor

When TinyMCE or OLIV CMS editing is required, inspect the existing CMS editor implementation rather than adding a new editor package.

### Admin dropdown actions

Inspect existing listing pages that already use the OLIV action dropdown.

The goal is **consistency**, not merely functional equivalence.

---

# 20. Do Not Hardcode What OLIV Already Centralizes

Avoid hardcoding values that should come from an existing source of truth.

Examples:

- statuses → Option class
- types → Option class
- select values → Option class/configuration
- URLs → existing OLIV URL helpers where applicable
- admin URLs → `getAdminUrl()` where used by the existing frontend pattern
- configuration → OLIV config/system configuration
- listing columns → bookmark definition
- permissions → existing authorization system
- layout composition → OLIV layout declarations

Before hardcoding a value, search the current plugin and OLIV implementation for an established source.

---

# 21. Authorization and Permissions

Hiding a menu item or action in Vue is not sufficient authorization.

When a feature is permission-controlled:

- follow existing OLIV authorization patterns,
- enforce authorization server-side,
- optionally hide/disable UI elements for UX,
- do not rely solely on frontend checks.

Inspect existing plugin permissions/resources before creating new permission naming conventions.

---

# 22. Controllers and Routes

Follow existing OLIV plugin controller organization:

```text
Http/Controllers/Admin/
Http/Controllers/Client/
```

where appropriate.

Reuse current route conventions and helpers.

Do not create duplicate route groups, middleware stacks, or authentication mechanisms if an existing plugin convention covers the feature.

Admin and client routes must remain clearly separated where the application architecture expects this.

---

# 23. Generated Files

Some OLIV files are generated/compiled from plugin declarations.

Before editing a generated file manually:

1. Determine what source generates it.
2. Modify the correct source declaration instead.
3. Run the appropriate OLIV compilation/update command if needed.

Do not make durable feature changes directly to generated output unless the prompt specifically requests that behavior.

Inspect OLIV's `oliv:plugins-update` documentation when plugin declarations, layouts, generated imports, menus, routes, configuration, or related compiled state changes.

---

# 24. Preserve Existing Architecture

Do not perform unrelated refactors.

Do not:

- rename unrelated classes,
- reorganize unrelated directories,
- replace working architecture,
- update dependencies without need,
- change formatting across unrelated files,
- migrate code to a different framework/library,
- modify OLIV internals merely to make the requested feature easier.

Keep changes scoped to the task.

---

# 25. Backward Compatibility

When modifying an existing feature:

- inspect current callers,
- inspect existing routes,
- inspect frontend consumers,
- inspect database assumptions,
- preserve public behavior unless the prompt explicitly changes it.

Do not silently break existing plugins or existing OLIV behavior.

---

# 26. Database Changes

When schema changes are required:

- create migrations using the plugin's migration convention,
- do not edit old production migrations solely to represent a new schema change unless explicitly instructed,
- preserve existing data,
- consider indexes and foreign keys,
- follow the database engine/version constraints of the application,
- inspect existing model/table naming conventions.

Do not introduce destructive migration behavior without explicit need.

---

# 27. Validation

Server-side validation remains authoritative.

Do not rely only on Vue/client validation.

Reuse existing validation patterns from the closest OLIV plugin.

When option classes define allowed values, validation should derive its allowed values from that source where practical.

---

# 28. Error Handling

Do not suppress exceptions merely to make a feature appear successful.

Follow the project's existing patterns for:

- validation errors,
- Inertia responses,
- JSON responses,
- redirects,
- session messages,
- logging.

Do not expose sensitive stack traces or credentials in user-facing output.

---

# 29. Security

Do not weaken:

- authentication,
- authorization,
- CSRF protection,
- validation,
- SQL safety,
- file validation,
- upload restrictions,
- webhook verification,
- secret handling.

Use Laravel/Eloquent/query builder APIs safely.

Do not concatenate untrusted input into SQL.

Do not expose API keys, secrets, tokens, passwords, private credentials, or environment variables to frontend code.

---

# 30. Testing and Verification

After making changes, perform the most relevant available verification.

Depending on the task, this may include:

```bash
php artisan test
```

specific PHP tests,

```bash
npm run build
```

or existing project-specific test/build commands.

Also inspect the changed code for:

- syntax errors,
- incorrect imports,
- incorrect paths,
- broken relative references,
- route mismatches,
- incorrect component names,
- accidental modifications to protected directories.

Do not claim a test passed unless it was actually executed successfully.

If testing could not be performed, state that clearly in the final report.

---

# 31. Agent Workflow

For every OLIV-related task, use this workflow:

## Step 1 — Understand the request

Identify the exact feature and scope.

## Step 2 — Inspect before editing

Inspect:

1. relevant project/plugin files,
2. a similar existing implementation,
3. relevant OLIV docs,
4. relevant OLIV package source when necessary.

## Step 3 — Identify the OLIV convention

Determine whether the task involves:

- listing/bookmark
- add/edit/view page
- FormData
- option classes
- system configuration
- model
- layout/component injection
- SCSS
- Vite
- Inertia
- routes
- permissions
- plugin compilation

## Step 4 — Implement the smallest correct change

Reuse existing conventions.

Avoid unnecessary abstractions.

## Step 5 — Verify

Run relevant tests/builds/checks.

## Step 6 — Report

Summarize:

- files changed,
- behavior implemented,
- OLIV patterns reused,
- tests/builds executed,
- any unresolved issue.

---

# 32. Decision Priority

When deciding how to implement something, use this priority order:

1. Explicit instructions in the current task.
2. This `AGENTS.md`.
3. Current installed OLIV implementation.
4. Current OLIV documentation.
5. Existing same-project plugin implementation.
6. Existing `/plugins/Opoink` implementation as a read-only reference.
7. Laravel/Inertia/Vue/Bootstrap framework conventions.
8. New custom implementation only when none of the above provides an appropriate solution.

---

# 33. Mandatory Rules Summary

The following rules are mandatory unless the current prompt explicitly overrides them:

1. Follow OLIV conventions.
2. Inspect similar OLIV code before inventing a new implementation.
3. Do not modify `/plugins/Opoink` unless explicitly required.
4. Do not modify `/vendor` unless explicitly required.
5. Do not minify source files unless explicitly required.
6. Use Bootstrap 5+ as the primary admin styling system.
7. Use scoped SCSS in admin only when custom styling is actually needed.
8. Frontend pages must use page-level SCSS that aggregates required component SCSS where applicable.
9. Use OLIV bookmark/listing conventions for admin listings.
10. Use the standard Bootstrap dropdown pattern for listing action buttons.
11. Do not reinvent admin View/Add/Edit pages.
12. Use `/plugins/Opoink/Email/resources/js/Pages/Admin/Emails/AddEdit.vue` as a primary Add/Edit reference.
13. Use OLIV `FormData`:

```js
import { FormData as _FormData } from '@@Plugins@@/Opoink/Liv/resources/js/Lib/form.data';

const formData = reactive(new _FormData());
```

14. Use `Lib/Option/*Option.php` as the single source of truth for predefined statuses/types/options.
15. Plugin models should follow the Liv model convention where appropriate.
16. Admin settings belong in `etc/admin/system.php` when using OLIV system configuration.
17. Respect OLIV layouts/component injection rather than creating a parallel system.
18. Do not manually edit generated files when a source declaration should be changed.
19. Keep changes focused on the requested task.
20. Verify changes before reporting completion.

---

# 34. Final Instruction to All Agents

**OLIV is not only a package dependency; it defines the application architecture and development conventions.**

A solution that technically works but bypasses an existing OLIV convention is generally **not considered a correct solution** for this repository.

When an established OLIV implementation exists, follow it.

When documentation and actual installed code differ, inspect the installed code and preserve compatibility with the OLIV version currently used by the application.

When the requested behavior truly requires deviation from OLIV conventions, keep the deviation narrow and explain it in the final report.
