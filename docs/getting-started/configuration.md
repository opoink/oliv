# Environment and configuration

Implementation: src/resources/config/oliv.php, src/resources/config/inertia.php, src/Console/Commands/Install.php, src/resources/viteplugins/import.plugin.js.

[Installation](installation.md) copies config templates and adds environment entries. It forcibly changes existing SESSION_DRIVER and CACHE_STORE to file; other values are added only when absent.

| Environment key | Consumer | Default or installer value |
| --- | --- | --- |
| OLIV_AUTH_ADMIN_USER_MODEL | oliv.auth_admin_user | Empty env falls back to Plugins_Opoink_Liv_Models_AdminUser |
| VITE_ADMIN_URL | oliv.vite_admin_url and frontend | Installer: admin_abc123; no config fallback |
| VITE_ADMIN_APP_NAME | oliv.vite_admin_app_name | Oliv Admin |
| VITE_OLIV_WELCOME_PAGE | oliv.vite_oliv_welcome_page | true |
| VITE_OLIV_THEME | Resolved Vite environment | Installer: default; transform has no fallback |
| VITE_INERTIA_SSR_PORT | SSR URL and Node server | 13714 |
| PHPMAILER_SMTPDEBUG | oliv.phpmailer_smtpdebug | 0 |
| PHPMAILER_HOST | oliv.phpmailer_host | smtp.example.com |
| PHPMAILER_SMTPAUTH | oliv.phpmailer_smtpauth | true |
| PHPMAILER_USERNAME | oliv.phpmailer_username | Config empty; installer user@example.com |
| PHPMAILER_PASSWORD | oliv.phpmailer_password | Config empty; installer secret |
| PHPMAILER_SMTPSECURE | oliv.phpmailer_smtpsecure | ssl |
| PHPMAILER_PORT | oliv.phpmailer_port | 465 |

PHPMAILER values serve Liv password-reset email. The [Email plugin](../bundled-plugins/email.md) instead uses [system settings](../admin/system-configuration.md).

## Configuration stores

| Application location | Meaning |
| --- | --- |
| plugins/config.json | Enabled identifiers in order |
| plugins/Vendor/Plugin/config.json | Metadata and optional Vite injection declarations |
| plugins/Vendor/Plugin/config/*.php | Inputs to config/plugins.php, except adminmenu |
| config/adminmenus.php | Compiled menus |
| plugins/Vendor/Plugin/etc/admin/system.php | Hierarchical setting definitions |
| storage/app/private/plugins/etc/admin | Compiled setting metadata/defaults |
| system_config table | Per-path overrides |
| plugin_event_listeners cache key | Compiled custom listeners |

Merge semantics differ; see [plugin configuration](../plugins/configuration.md). Compilation does not refresh Laravel configuration cache.

The Inertia template enables SSR at localhost and the configured port. Its testing page paths include only resources/js/Pages, omitting plugin and generated directories; page-existence tests need suitable host configuration.

---
[Installation](installation.md) · [Cache lifecycle](../reference/caching.md) · [Documentation index](../README.md)
