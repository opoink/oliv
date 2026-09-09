# Listings and query filters

Implementation: src/resources/plugins/Opoink/Liv/Lib/AdminListing.php, QryFilter.php, resources/js/Components/Admin/Listing.vue and ListingBookmark.vue.

OLIV contains both older controller-built listings and a bookmark-backed listing service. EmailController demonstrates the latter; admin users/CMS/roles still contain separate query/pagination code.

## AdminListing API

Namespace: Plugins\Opoink\Liv\Lib.

| Method | Behavior |
| --- | --- |
| setTargetDefaultBookmark(string $targetFile) | Stores base_path($targetFile); returns this |
| setNamespace(string $namespace) | Sets listing identity; returns this |
| getNamespace() | Returns identity |
| getBookmark() | Fetches/creates current admin's bookmark, decodes config, expands filter options |
| getCollumns() | Returns keys ordered by bookmark positions; spelling is intentional |
| getPaginator(string $model, ?callable $callback = null) | Returns paginator and sort_order array |
| updateBookmark() | Applies request page, sorting and filters; persists changes |
| getInertiaVersion() | Returns framework Inertia version |
| saveVisibleColumns(int $id, array $columns) | Updates visibility in the specified bookmark |

These methods have no declared return types. Call getBookmark/getPaginator before getCollumns because it accesses the already initialized bookmark. A new service instance per listing avoids cached columns/bookmark state crossing namespaces.

~~~php
$listing = app(\Plugins\Opoink\Liv\Lib\AdminListing::class);
$result = $listing
    ->setTargetDefaultBookmark('plugins/Opoink/Email/Lib/Bookmarks/emails.json')
    ->setNamespace('opoink_email_listing')
    ->getPaginator(\Plugins\Opoink\Email\Models\Emails::class);
~~~

Despite its PHPDoc claiming an absolute path, setTargetDefaultBookmark calls base_path; bundled usage supplies an application-relative path.

getPaginator first updates the bookmark, creates Model::query(), invokes the optional callback($query, $bookmark, $columns), writes bookmark filters/sort onto Request, applies QryFilter, changes Laravel's current-page resolver, then paginate(pageSize). It returns ['paginator' => ..., 'sort_order' => ...].

## Query contract

~~~json
{
  "filters": [
    {"key": "name", "condition": "LIKE", "value": "newsletter"},
    {"key": "id", "values": {"from": 10, "to": 30}}
  ],
  "sort_order": {"key": "id", "value": "desc"}
}
~~~

QryFilter::addFieldMap(string $key, string $mapKey) maps public column names to SQL columns. setFilters(Request, Eloquent\Builder, array $columns) accepts only keys in the allowed columns. Default condition is LIKE with surrounding wildcards. Ranges use between, >=, or <=. PHP empty/truthiness checks cause zero-like values to be skipped. Conditions are passed to the query builder without a separate OLIV operator allowlist.

setSortOrder with the same arguments returns the applied key/value pair. Only lowercase asc/desc and allowed columns are accepted; fallback is model primary key descending. AdminListing's missing-direction default is uppercase ASC, which fails that lowercase check.

## Frontend contracts

Listing expects propsdata.listing.columns and a serialized paginator. It supports numbered links or cursor prev/next URLs. Slots are action_column_left, th_<column>, <column> with {item}, and item with {item} for actions.

ListingBookmark expects propsdata plus bm, an instance of [Bookmark.js](bookmarks.md). It creates sorting/filter UI and forwards cell slots; its action slot is **itemaction**. Available filter components are text, select, range, and date range; unknown names resolve to null.

See [bookmark schema](bookmarks.md) for persistence and visibility settings.

---
[Frontend helpers](../frontend/helpers-and-components.md) · [Documentation index](../README.md)
