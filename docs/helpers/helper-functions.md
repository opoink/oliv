# PHP helpers and filesystem libraries

Implementation: functions.php, src/Lib/DataObject.php, Dirmanager.php, Writer.php, and src/Facades.

Composer autoloads functions.php. It defines ROOT by walking three parents above the package directory and DS as DIRECTORY_SEPARATOR. This assumes the standard vendor/opoink/oliv location. Functions are guarded by function_exists; constants are not guarded.

## Global APIs

| Signature | Return/side effects |
| --- | --- |
| getPath($path) | ROOT plus normalized slash-separated path; strips leading separators |
| getAdminUrl(?string $path=null, ?array $params=null) | Prefix alone when no truthy path; otherwise "/" + prefix + path and optional query |
| isAdminRoute() | Whether first request-path segment equals configured prefix |
| getRolesResource($adminId) | Role-resource map, memoized in session; parameter is a role ID |
| getAuthAdminUser() | Admin guard user or null |
| isRoleAllowed($resource) | Scalar resource check; assumes logged-in admin; broken array branch |
| getPluginsConfig() | Globally memoized DataObject from plugins/config.json |
| getPluginDir($plugin) | Application plugins path with every underscore replaced by separator |
| b64UrlEncode($data) | Base64 using URL alphabet, no padding |
| b64UrlDecode($data) | Restores padding/alphabet, non-strict base64_decode |
| getGlobalComponentName($path) | Derived Vue registration/import identifier |
| inertiaRender(string $component, array or Arrayable $props=[]) | [Detailed render/asset behavior](inertia-render.md) |

No listed helper declares a return type. getPath/getPluginDir are path constructors, not traversal validators. getAdminUrl does not insert a missing slash between prefix and a supplied path: pass "/settings", not "settings".

~~~php
$path = getPluginDir('Opoink_Email');
$url = getAdminUrl('/emails', ['page' => 2]);
$plugins = getPluginsConfig()->getData('plugins', []);
~~~

See [authentication](../admin/authentication.md) and [plugin registration](../plugins/plugin-structure.md) for state and validation limitations.

## DataObject

Namespace Opoink\Oliv\Lib. __construct(array $data=[]) stores the data.

| Method | Behavior |
| --- | --- |
| setData($key,$value=null) | Array key argument replaces all data; otherwise assigns one key; returns this |
| getData($keys='',$default=null) | Empty key returns all data; slash traversal returns nested value/default |
| unsetData($key=null) | Clears all, one string key, or array of keys; returns this |
| _underscore($name) | Converts capitals/digits to underscore form; static cache |
| camelCaseToSpace($name,$casing='strtolower') | Space-separated formatting with optional upper/title/first case |
| __call($method,$args) | Magic get/set/uns/has; unknown prefixes return null |

~~~php
$data = new \Opoink\Oliv\Lib\DataObject(['page' => ['size' => 20]]);
$size = $data->getData('page/size', 10);
$data->setAdminUser($user); // admin_user
~~~

Magic getFoo($index) appends a truthy slash subkey; it is not a default-value argument. getData uses isset, so null often behaves as missing. Although there is a nested-DataObject branch, the preceding array-style offset access can make mixed object traversal unreliable; ordinary nested arrays are the straightforward supported shape.

## Dirmanager and Writer

Dirmanager::create($path) / createDir($path) recursively mkdir with 0777 and return true only when newly created. deleteDir($dirPath) recursively unlinks glob("*") results and rmdir; dotfiles may remain because of that glob. copyDir($src,$dst,$copyCallback=null) overwrites files recursively and calls callback(source,destination) after each file copy; a missing source throws.

Writer extends Dirmanager. setDirPath immediately creates the directory; setData, setFilename, setFileextension are fluent, with matching getters. write() opens "<dir>/<filename>.<extension>" in mode w and writes the content; implicit null return.

~~~php
$writer = new \Opoink\Oliv\Lib\Writer();
$writer->setDirPath(storage_path('app/example'))
    ->setFilename('message')
    ->setFileextension('txt')
    ->setData('Example');
$writer->write();
~~~

This overwrites the target. There is no locking, atomic rename, boundary validation, or checked partial-write handling. These utilities power [installation](../cli/oliv-install.md), compilation and the [CMS writer](../bundled-plugins/cms.md).

---
[Providers/facades](../architecture/service-providers.md) · [Documentation index](../README.md)
