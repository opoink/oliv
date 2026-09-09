# Email templates, SMTP, and queue

Implementation: src/resources/plugins/Opoink/Email/{Lib/Mailer.php,Lib/Option,Models,Http/Controllers/Admin/EmailController.php,etc/admin/system.php,migrations,resources/views/default_template.blade.php}.

## Template administration

EmailController uses [bookmark-backed listings](../admin/listings.md), namespace opoink_email_listing, and Lib/Bookmarks/emails.json. Resource checks are oliv_email_list, oliv_email_add_edit and oliv_email_delete.

Templates store name, subject, content and optional css in emails. Emails::getTemplate(string $name) returns the first matching model; getDefaultTemplate() looks for the constant DEFAULT_TEMPLATE_NAME = default_template. Names have no unique schema constraint.

The migration inserts default_template from the installed Blade file. Some controller redirects reference admin.emails, but the supplied listing route is unnamed.

## Mailer API

Namespace: Plugins\Opoink\Email\Lib\Mailer. Methods have no declared return types.

| Method | Behavior |
| --- | --- |
| render(string $__php, array $__data) | Extracts data with EXTR_SKIP and evaluates PHP in an output buffer; returns rendered string |
| renderBlade(string $template, array $params) | Blade compileString followed by render |
| getTemplate(string $name) | Returns Emails model/null |
| getDefaultTemplate() | Returns rendered base wrapper string |
| send(array $recipients, string $subject, string $body, ?Closure $beforeSend=null) | Sends HTML through PHPMailer; returns no result |
| sendPending(int $limit=30, ?Closure $beforeFetchQueue=null) | Delivers due pending rows and updates status |

getDefaultTemplate uses the database wrapper if present, otherwise the installed file. It resolves logo, companyname, tagline, companyaddress, companyphone, companyemail, facebook, twitter and website from the system-settings variablevalues group. It deliberately leaves subject/content as Blade expressions for a subsequent render.

~~~php
$mailer = app(\Plugins\Opoink\Email\Lib\Mailer::class);
$body = $mailer->renderBlade($mailer->getDefaultTemplate(), [
    'subject' => 'Example',
    'content' => '<p>Example message</p>',
]);
// Sending is an explicit application action:
// $mailer->send([['email' => 'recipient@example.com', 'name' => 'Recipient']], 'Example', $body);
~~~

Templates compile and eval PHP; they must be trusted. The stored css column is not automatically applied by Mailer.

## SMTP configuration

send reads [system configuration](../admin/system-configuration.md) beneath opoink_liv__general/website/email/: smtpdebug, host, smtpauth, username, password, smtpsecure, port. smtpauth is true only for YesNo::YES. Username becomes the From address. Each recipient requires email and name.

beforeSend receives the PHPMailer object after addresses, but before Subject and Body are assigned. It can add attachments or other settings; subsequent assignments can overwrite its subject/body edits.

The implementation disables TLS peer/name verification and allows self-signed certificates. It sends HTML with high-priority headers and no automatic plain-text body. Exceptions are wrapped in an Exception containing ErrorInfo.

Liv password-reset mail uses separate [environment SMTP keys](../admin/authentication.md).

## Queue contract

email_queue requires type, recipient, subject and body. Status defaults to **Pending**. Optional fields include scheduled_at, sent_at, fail_message, others and param_a through param_d; attempts starts at zero. Status constants are Pending, Sending, Sent and Failed.

sendPending queries Pending rows with scheduled_at <= now(), applies limit, then an optional callback($query). Null scheduled_at rows do not meet the due-time comparison. Each fetched row is sent to one recipient with blank name; success marks Sent/sent_at, failure marks Failed/fail_message.

**Limitations:** no locking/claiming transition to Sending, no enforced ordering, no retry policy for Failed rows, and no automatic scheduling. Concurrent calls can select the same rows. The expression attempts = attempts++ writes the old value back instead of reliably incrementing it.

Wire delivery explicitly through [plugin console routes](../plugins/routes-and-scheduling.md) if needed. The package supplies neither a worker command nor a scheduled sendPending call.

---
[Installation](../getting-started/installation.md) · [Database](../reference/database.md) · [Documentation index](../README.md)
