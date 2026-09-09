# Listing bookmarks

Implementation: src/resources/plugins/Opoink/Liv/Models/ListingBookmark.php, Lib/AdminListing.php, Http/Controllers/Admin/AdminListing/Bookmark.php, resources/js/Lib/Bookmark.js. Example: src/resources/plugins/Opoink/Email/Lib/Bookmarks/emails.json.

Bookmarks persist one current listing configuration per admin and namespace. There is no implemented named-view collection in the supplied JSON.

## Configuration shape

~~~json
{
  "current": {
    "filters": [],
    "paging": {
      "pageSize": 20,
      "current": 1,
      "options": {"20": {"value": 20, "label": 20}},
      "value": 20
    },
    "columns": {
      "id": {
        "visible": true,
        "sorting": true,
        "filter": true,
        "filter_input": "FilterFieldRange"
      },
      "name": {
        "visible": true,
        "sorting": true,
        "filter": true,
        "filter_input": "FilterFieldText"
      }
    },
    "positions": {"id": 0, "name": 1},
    "attributes": {"id": "ID", "name": "Name"},
    "sort_order": {"key": "id", "value": "desc"}
  }
}
~~~

sort_order is optional. Consumers directly access current, filters, paging.pageSize/current, columns, positions, and attributes; no schema validation/default normalization makes missing keys safe. The bundled sample includes page-size options 20/30/50/100/200, but the supplied listing UI does not implement a general page-size save workflow.

Column filter_options can be an options array or a PHP class string; getBookmark resolves string classes and calls toOptionArray(). Expanded arrays can be saved into current config during later updates.

positions maps field names to numeric order. getCollumns reverses this into index-to-name; duplicate positions overwrite earlier fields. attributes values can be strings or objects with frontend_label in Bookmark.js; not every filter-label consumer handles those objects identically.

## Persistence and request updates

ListingBookmark::getBookmark(string $namespace, ?callable $callback = null) scopes retrieval by authenticated admin ID and namespace. If absent, AdminListing uses the default JSON to create a row via createBookmark(int $adminsId, string $namespace, string $config).

Existing bookmarks are not migrated when default JSON changes. The table has no unique admin/namespace constraint.

updateBookmark reads page, sort_order, and apply_filter=1 from the request. Applying filters with no filters clears them. It does not reset page to 1 automatically when filters change. It saves only when JSON differs and forgets a listing_bookmark_<namespace>_<id> cache key, although the read-through cache code is commented out.

## Frontend API

Bookmark.js default-exports class bookmark. Construct it with propsdata containing listing and bookmark:

~~~js
import Bookmark from '@@Plugins@@/Opoink/Liv/resources/js/Lib/Bookmark.js';
const bm = new Bookmark(propsdata);
bm.setColumns();
~~~

Methods include setConfigColumns(), setColumns(), getColumnLabel(column), columnShowHide(), updateBookmarkColumns(), countVisibleTrue(label), and getColumnValue(column, value). It sorts positions, excludes ids from its dropdown, and updates visible columns in propsdata.listing.columns.

Visibility changes POST {id, columns} to the admin bookmark endpoint. The controller validates row existence and columns presence, but does not enforce row ownership or a detailed columns schema. AdminListing then looks up by ID directly. Authentication alone does not make this an ownership-safe API.

---
[Listings](listings.md) · [Database](../reference/database.md) · [Documentation index](../README.md)
