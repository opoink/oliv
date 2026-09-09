# CMS blocks, pages, and editors

Implementation: src/resources/plugins/Opoink/Cms/{Http/Controllers/Admin,Models,Lib/Cms.php,resources/js/Components/Editor.vue,resources/js/Lib/grapejs/grape.js,routes/web.php,migrations}.

The bundled CMS provides authenticated admin CRUD for reusable blocks and stored pages. It does not supply a public identifier-to-page route, publish command, or automatic frontend registration for saved content.

## Storage and controllers

CmsBlock uses cms_block; CmsPages uses cms_pages. Both inherit the [Liv base model](../plugins/models.md). Blocks store name, identifier and JSON-encoded content. Pages add meta_title, meta_keywords, meta_description and nullable others.

New identifiers replace spaces with hyphens, strip other non-alphanumeric/hyphen characters, and lowercase the result. Existing identifiers are retained during edits. Duplicate detection is a query, not a unique schema constraint.

Block routes support list/add/edit/save/delete. Page routes support list/add/edit/save, with no supplied delete action. Lists use cursor pagination at 15 rows. Block listing includes inline filters; page listing does not apply its incoming filters.

Resource checks exist for add/edit and saves, and block deletion. Listing controllers do not enforce their own resource checks beyond route-level adminauth. Deleting a block also removes its computed generated component directory.

## Editor payload

Editor.vue accepts selector and modelValue (a JSON string), and exposes emitContent(). Its payload shape is:

~~~json
{
  "editor": "gjs",
  "content": {
    "html": "<p>Example</p>",
    "css": "p { color: blue; }",
    "data": {}
  }
}
~~~

For tinymce and plaintext, content is a string. The component offers editor selection, initializes TinyMCE or GrapesJS, and loads existing project data. The block editor calls emitContent(), then JSON.parse on the form string before posting the content object. Controllers JSON-encode that object for storage; do not post an already encoded string if following this workflow.

GrapesJS uses a singleton module and a custom window storage provider backed by window.oliv_cms.gjs_project. Its asset manager embeds Base64. It configures blocks, forms, countdown, export, custom code, PostCSS, tooltips, image editing, typed text and background styles. The image editor loads additional CDN scripts/styles. A three-column project JSON supplies the initial project.

## Separate component writer API

Namespace: Plugins\Opoink\Cms\Lib\Cms.

| Method | Behavior |
| --- | --- |
| __construct(Opoink\Oliv\Lib\Writer $writer) | Injects file writer |
| getDirName($identifier) | Hyphen-separated words become concatenated title case |
| getComponentPath($dirName, $type='Blocks') | Returns resources/js/Cms/<type>/<dirName> under app root |
| saveComponent($identifier, $content, $type='Blocks') | Writes VueComponent.vue and, for gjs, VueComponent.scss |

These methods have no declared return types. saveComponent expects a decoded content array. gjs CSS is wrapped in an ID selector; the first matched script body is moved into onMounted while matching script tags are removed from template HTML. tinymce content is used directly. plaintext has no implemented branch, producing an empty template body.

~~~php
app(\Plugins\Opoink\Cms\Lib\Cms::class)->saveComponent('example-block', [
    'editor' => 'tinymce',
    'content' => '<p>Example</p>',
]);
~~~

This writes files. It is not called by the supplied save controllers or bundled event lists. Generated Cms/Blocks files are outside the normal [page glob](../frontend/vue.md), so a host must import/use them explicitly and build assets.

## Implementation gaps

Editor.emitContent uses this.$emit inside script setup without defineEmits. Its functioning is not established. GrapesJS lists grapesjs-tabs as a plugin while its import/registration is commented out; setContainer stores a value but init hardcodes #gjs. Browser lifecycle code assumes a global TinyMCE asset exists.

There is no automatic save-to-Vue publishing wiring. Treat editor content and generated scripts/templates as trusted executable content, not sanitized user submissions. No implementation changes were made.

---
[Installation](../getting-started/installation.md) · [Media](media.md) · [Documentation index](../README.md)
