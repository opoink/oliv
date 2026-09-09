# System configuration and option classes

Implementation: src/Lib/Plugin/MergeSystemConfig.php and src/resources/plugins/Opoink/Liv/{etc/admin/system.php,Lib/SystemConfig.php,Models/SystemConfig.php,Http/Controllers/Admin/Settings/Settings.php,resources/js/GlobalComponents/Admin/SystemConfigFieldGroups.vue}.

## Declaration schema

Each plugin's etc/admin/system.php returns a tree. The usual levels are tab → section → group → field; groups can nest.

~~~php
return [[
    'name' => 'vendor_plugin__general',
    'label' => 'General',
    'type' => 'tab',
    'sort_order' => 10,
    'children' => [[
        'name' => 'website',
        'label' => 'Website',
        'type' => 'section',
        'children' => [[
            'name' => 'general',
            'label' => 'General',
            'type' => 'group',
            'children' => [[
                'name' => 'site_name',
                'label' => 'Site Name',
                'type' => 'field',
                'field' => ['type' => 'text'],
                'value' => 'Example',
            ]],
        ]],
    ]],
]];
~~~

Names identify siblings. type must be tab, section, group, or field for value compilation; missing/unknown types throw. label, sort_order, html_attributes, comment, value and children provide UI/default data. A field's **field.type** is distinct from node type.

Supported UI field types are text, select, multiselect, email, password and textarea. select/multiselect use field.options arrays of {label,value}. File upload is explicitly commented out. Labels/comments can render HTML; definitions are trusted code. html_attributes are bound on supported elements; textarea does not apply the same attribute binding.

## Merge and compilation

[Plugin update](../plugins/plugin-update.md) calls addSystemConfig(array), then merge(). Matching named siblings merge recursively; later scalar values replace earlier ones, other arrays use array_replace_recursive, and children merge by name. Single named children can be normalized to lists. There is no special remove flag.

Sorting recursively casts sort_order to int. Missing order receives a counter beginning at 99999 and increasing by 10. Duplicate order slots are incremented until free, changing the stored value. The counter spans recursive processing, not just one sibling list.

Outputs under storage/app/private/plugins/etc/admin:

| Output | Purpose |
| --- | --- |
| system.php | Entire merged tree |
| system_value.php | Nested defaults keyed by node names |
| system_tab_section.php | First two levels with deeper children removed |
| system_tab_section/<tab>.<section>.php | Groups for each section |

Old section files are not deleted. Public toValue(array $merged) returns nested default values; flattenConfig(array $nodes, string $prefix = ''): array separately returns dot-path field values but is not the persistence format used by runtime settings.

## Runtime API

Plugins\Opoink\Liv\Lib\SystemConfig extends DataObject and has a facade of the same name under Lib\Facades.

~~~php
use Plugins\Opoink\Liv\Lib\Facades\SystemConfig;
$name = SystemConfig::getValue('opoink_liv__general/website/general/site_name');
~~~

getValue(string $key) trims slashes and first looks for a system_config row. If absent, it reads compiled defaults using slash traversal. Results and their source are memoized per instance. isSystemValue(string $key) reports whether the fallback default was used.

assignValue(array $data, string $prefix = '') returns metadata with _value and _is_system_value. Multiselect stored strings become comma-split arrays.

~~~php
SystemConfig::saveSystemConfig([
    'opoink_liv__general/website/general/site_name' => [
        'value' => 'My site',
        'is_system_value' => false,
    ],
]);
SystemConfig::resetLoadedValue();
~~~

saveSystemConfig(array $data) upserts overrides; is_system_value true deletes the row. deleteByPath(string $path) removes an override. resetLoadedValue() clears memoized lookups and returns this. These methods do not all normalize path strings identically; use canonical paths without surrounding slashes. save does not automatically reset the value cache.

## UI and limitations

Settings selects tab/section from request parameters and includes the corresponding compiled PHP file. Its UI serializes multiselect arrays to comma-separated strings and posts data/tab/section. A transaction wraps saving; refreshed field metadata is returned after resetting cached values.

The recursive global component is registered during [installation/compilation](../frontend/vue.md). It exposes fieldGroups and setFieldGroups(data), and disables fields marked "Use system value".

There is no per-field server validation against declarations, no setting-path allowlist, no encryption for password values, and no action-level role check beyond adminauth. Requested tab/section values are used in include-path construction without an explicit whitelist. The empty-settings path can leave fieldGroups undefined. loadConfig's isLoaded flag is checked but never set true.

## Options

OptionsInterface declares toOptionArray(), getOptions(), getLabel(string $key). Options implements labels by title-casing underscore-separated values; its toOptionArray uses values as option values, while getLabel indexes keys. YesNo separately returns yes/no pairs and does not implement the interface.

Email supplies SmtpOption with PHPMailer debug constants as values and EmailQueueOption with status strings. Listing filter_options resolves class strings at runtime; system options are evaluated during compilation.

---
[Configuration stores](../getting-started/configuration.md) · [Email settings](../bundled-plugins/email.md) · [Documentation index](../README.md)
