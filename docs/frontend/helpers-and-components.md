# Frontend helpers and reusable components

Implementation: src/resources/resources/js/{common.js,Plugins/filters.js} and src/resources/plugins/Opoink/Liv/resources/js/{Lib,States,Components}.

These utilities are installed application code, imported through [Vite aliases](asset-resolution.md).

## Liv common.js exports

| Export | Contract/side effect |
| --- | --- |
| debounce(func, wait, immediate=false) | Returns delayed wrapper; optional leading call |
| bytesToSize(bytes) | Formats binary-size unit; Math.round does not honor its second precision argument |
| getAdminUrl(path='/', paramsObj=null) | Reads Inertia admin_url and appends query |
| getUrl(path='/', paramsObj=null) | Builds path/query string |
| http_build_query(obj, num_prefix, temp_key) | Recursively serializes bracket-style query keys; mutates scalar values to strings |
| isRoleAllowed(resource) | Shared admin resource lookup; broken array branch |
| base64EncodeUrl(str) | Browser btoa, URL alphabet, no padding |
| base64DecodeUrl(str) | Custom decoder uses ordinary Base64 alphabet; URL-symbol normalization is absent |
| urlencode(str), urldecode(str) | Custom character-code encoding; not a general UTF-8 URL codec |
| backToPrevUrl(fallback_url) | router.visit using window.url_history; assumes history object exists |
| cloneObj(obj) | JSON stringify/parse clone; drops non-JSON types |

The root common.js provides trim/rtrim/ltrim and getComponentName(path), used by the [Vite injector](component-injection.md). Its path-segment algorithm differs from PHP global component naming.

Filters installs $getAdminUrl using import.meta.env.VITE_ADMIN_URL or "admin", distinct from the Inertia-prop helper. $isActiveTab(value, defaultvalue='', queryParam='active-tab') depends on global route(). The shell does not install that route map.

## Forms, listing state and scripts

FormData from Lib/form.data.js has fields, form, setForm() calling useForm(fields), and setErrors(errors). Array errors become danger toasts; keyed arrays become form errors. It is not browser native FormData.

[ListingFilter](../admin/listings.md) exposes addFieldToFilter, showFilters, applyFilters, setSortOrder, removeFilter, setFilterValues. applyFilters visits the current pathname with apply_filter=1, sorting and filter arrays; it discards unrelated query parameters. Numeric zero ranges are skipped by truthiness checks.

scriptloader.load(url, isCss=false) returns a Promise and appends a script or stylesheet to the document. URLs are marked before completion; a concurrent second call resolves immediately rather than sharing the first in-flight promise. Failures clear the marker for retries. It requires a browser.

## Reactive state

~~~js
import { toast } from '@@Plugins@@/Opoink/Liv/resources/js/States/toast.js';
import { loader } from '@@Plugins@@/Opoink/Liv/resources/js/States/loader.js';

loader.setLoader(true, 'Saving...');
toast.add('Saved', 'success', 8000);
loader.setLoader(false);
~~~

toast exposes list, add(message,type='success',timeout=8000), clear(index), clearAll(). Timers remove index zero, not a stable message ID. Toast and Loader render their messages with v-html, so pass trusted content.

loader exposes isActive/content, setLoader(isActive,content=''), and setContent(content). AdminSideTabs and adminSideNav are covered in [tabs/layouts](../admin/tabs-and-layouts.md).

## Components

| Component | Inputs/extension point |
| --- | --- |
| Listing.vue | propsdata; header/cell/action slots; [listing contract](../admin/listings.md) |
| ListingBookmark.vue | propsdata and bm; filter/visibility UI; itemaction slot |
| ListingThSort.vue | sortorder, column_name; label slot |
| FilterFieldText/Select/Range | field, name, modelValue; update:modelValue; Select adds options |
| FilterFieldDateRange | field, name, modelValue with from/to; datepicker mutation |
| ActiveFilterFieldText/Range | listingFilter, field, label; clears filter and emits removeFilter |
| ModalConfirmmation.vue | id/title/button labels/onconfirm/onclose; default content slot |
| Sortable.vue | items, htmlclass, onupdatecallback; sortableitem slot with item |
| OwlCarousel.vue | elid, owlconfig; default slot |
| Toast.vue / Loader.vue | Consume reactive state |
| SystemConfigFieldGroups.vue | Recursive [setting groups](../admin/system-configuration.md) |

The modal's _onClose checks onconfirm's type before calling onclose. Sortable uses a fixed sortable-items ID and reports SortableJS events without automatically reordering the source array. OwlCarousel polls every 100 ms for initialization, stopping after roughly 500 attempts; its custom owlconfig replaces defaults rather than merging.

DateRange converts selected dates through toJSON (UTC) and its clear-To branch incorrectly clears from. Several widgets lack teardown for document listeners/plugin instances. These are current limitations, not additional supported APIs.

---
[Vue setup](vue.md) · [Documentation index](../README.md)
