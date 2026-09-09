# Known limitations and implementation gaps

These findings describe the inspected code. They are not fixes or claims that every path was exercised in a running application.

## Core, compilation and layouts

- The provider's publish source src/config/oliv.php is missing; [installation](../cli/oliv-install.md) copies the real template.
- PageLayout middleware registration is commented out. [Compile-time generation](../layouts/layout-middleware.md) is active.
- The old README says the render helper does not replace explicit component selection; [current functions.php does](../helpers/inertia-render.md).
- README alias-deduplication claims differ from Layout: @@Plugins@@ normalization is commented out.
- Compiler validation is sparse, writes are not transactional, providers are not removed, and old system-section files are not pruned.
- API route collection is commented out; schedule:run has no active custom handle method.
- No plugin dependency/version resolver or plugin upgrade-hook lifecycle exists.
- inertiaRender dereferences route() without an effective null guard and hardcodes manifest/build locations.
- Generated page collisions are not detected; declared targets can overwrite unmarked files, although cleanup only deletes owned files.

## Frontend

- [page_assets has no supplied consumer](../frontend/css-loading.md); collecting CSS is not equivalent to emitting links.
- Theme replacement reads UTF-8 source under the original module ID; binary/public-file overrides are not established.
- Vite injector requires exact markers and does not deduplicate imports or report missing markers.
- Global component naming does not detect collisions.
- Filters.$isActiveTab assumes global route(), while the shell omits Ziggy output.
- PHP and JS isRoleAllowed array branches are broken.
- DateRange clear-To clears from; modal close checks the wrong callback; Sortable has a fixed ID; scriptloader resolves duplicate in-flight requests early.
- Browser-only editor/plugin dependencies need independent SSR validation.

## Admin and persistence

[Authentication documentation](../admin/authentication.md) details missing action-level role checks in role/settings operations, self-edit role assignment, direct admin-model serialization, and recovery limitations. [Bookmark writes](../admin/bookmarks.md) lack ownership validation.

[System settings](../admin/system-configuration.md) do not validate posted paths/values against declarations, encrypt password fields, or whitelist requested include-file selectors. Default and database stores can diverge until explicit refresh.

The [base model](../plugins/models.md) dispatches custom events even for quiet operations. Post-commit callbacks cannot undo a completed transaction; delete discards its parent's return value; only Exception is caught.

The bundled users migration alters an existing table, sorts early, and has no reversal. Starter admin credentials are fixed in migration source.

## Bundled plugins

[CMS](../bundled-plugins/cms.md): no supplied public page route or automatic saveComponent call; page deletion and plaintext component generation are absent; Editor emits through this.$emit in script setup; GrapesJS tabs registration is commented while requested.

[Email](../bundled-plugins/email.md): unnamed listing route conflicts with redirects; templates eval compiled PHP; TLS verification is disabled; queue processing has no claim lock, retry scheduling or reliable attempt increment. No scheduler wires sendPending.

[Media](../bundled-plugins/media.md): public private-storage access has no path-containment/auth/size boundary; missing-source handling is inconsistent; dd prevents placeholder fallback; no generated-image invalidation exists.

## What remains uncertain

Package inspection cannot establish deployed middleware ordering, differences in installed application copies, web-server static-file behavior, actual SMTP delivery, editor runtime compatibility, or full dev/production/SSR success. No installer, migration, build or delivery operation was run for this documentation.

The older Liv/Lib/Gumlet copy exists, while Media extends the Composer Gumlet class. Its presence is not proof that the copy is an active dependency. Comments/TODOs are labeled as such; no formal deprecation policy is inferred.

---
[Architecture](../architecture/overview.md) · [Documentation index](../README.md)
