# Bundled database tables

Source: src/resources/plugins/Opoink/{Liv,Cms,Email}/migrations. Media has no bundled migrations. This is source-derived schema documentation, not a report of the host database's current tables.

All created tables use an id and created_at/updated_at timestamps with current defaults and update behavior. [Migration command extensions](../cli/migrations-and-scheduler.md) add enabled plugin paths.

| Table | Principal columns and constraints |
| --- | --- |
| users (altered) | Adds firstname after name, **midlename** (spelling), lastname; changes timestamps; requires existing table |
| admin_user_roles | role_name |
| admin_user_roles_resource | resource, nullable admin_user_role_id; indexed FK cascades on role deletion |
| admins | firstname, lastname, unique email, password, nullable admin_type/role ID/reset code/reset expiry; role FK sets null on delete |
| attributes | table_name, table_id, name, longText value; no bundled generic attribute model |
| system_config | path string(255), longText value; no unique path constraint |
| listing_bookmark | nullable admins_id FK cascading on admin deletion, longText namespace/config; no unique admin/namespace constraint |
| cms_block | name/identifier string(255), longText content |
| cms_pages | name/identifier, content, meta_title/meta_keywords/meta_description, nullable longText others |
| emails | name/subject, longText content, nullable longText css |
| email_queue | type/recipient/subject/body, status, schedule/sent timestamps, attempts, fail_message, others, param_a–param_d |

email_queue status defaults to Pending; attempts defaults to zero; scheduled_at/sent_at and diagnostic/extra text fields are nullable. See [queue processing](../bundled-plugins/email.md) for behavior of null schedules and retries.

The admins migration inserts admin@domain.com with hashed password admin and admin_type super_admin. The emails migration inserts the installed default_template Blade content through the Emails model.

## Migration limitations

The users migration's filename begins 2014 but it alters rather than creates users. Fresh host migration order must ensure that table exists first. Its down() is empty and does not remove the added columns. Other listed down() methods drop their table and data.

CMS identifiers and email template names are checked or selected in code without unique database constraints. Settings paths and bookmark identities likewise lack uniqueness guarantees under concurrent creation.

No standalone database/ directory, factories, or seeders are supplied at package root. Initialization records are embedded in migrations.

---
[Installation prerequisites](../getting-started/requirements.md) · [Models](../plugins/models.md) · [Documentation index](../README.md)
