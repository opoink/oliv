# Media and image cache

Implementation: src/resources/plugins/Opoink/Media/{routes/web.php,Http/Controllers/Client/MediaCacheImage.php,Lib/MediaDir.php,Lib/ImageManager.php,Lib/ImageResize.php,resources/js/Lib/common.js}.

## URL API

~~~js
import { getImageUrl } from '@@Plugins@@/Opoink/Media/resources/js/Lib/common.js';
const url = getImageUrl('uploads/example.jpg', 320, 200, 'crop', 2, 0);
~~~

Signature: getImageUrl(path, width=0, height=0, render_type='crop', crop_position='na', allow_enlarge=0). It returns:

~~~text
/m/c/image/width-320/height-200/render_type-crop/crop_position-2/allow_enlarge-0/p/uploads/example.webp
~~~

convertToWebpIfNeeded(filePath) replaces the final extension with .webp unless already exactly .webp. Values/path are concatenated without URL encoding.

The public wildcard route parses the first /p/ delimiter, then option segments split on their first hyphen. Missing delimiter aborts 404. Duplicate option keys use the last value.

## PHP processing

ImageManager extends MediaDir and exposes chainable setters:

| Signature | Meaning |
| --- | --- |
| setDesination(string $destination) | Appends relative destination to public path; spelling is intentional |
| setSize(?float $width=null, ?float $height=null, int|string|null $allowEnlarge=null) | Strips nondigits, stores dimensions; enlargement only when == 1 |
| setPath(string $path, string $dir='private') | Looks under storage/app/<dir>, with conditional plugin fallback |
| setRenderType(?string $renderType=null, $cropPosition=null) | crop or best-fit fallback; validates crop position |
| getRealPath() | Resolves source format candidates |
| saveImage() | Resizes, writes output, sends raw headers/body, exits |
| renderNoImage(string $basename) | Renders bundled placeholder and exits |

Missing dimensions use source dimensions. Crop positions are 1 top, 2 center, 3 bottom, 4 left, 5 right, 6 top-center. Invalid positions, including the JS default "na", become center before rendering. Other render types use resizeToBestFit.

For a missing .webp source, getRealPath probes webp, jpg, jpeg, png. Otherwise it returns the requested path even when absent. Consequently, setPath's plugin fallback works when the first lookup produces an empty real path, but does not reliably fall back for missing non-webp sources. Reusing an instance can also retain realPath state; the controller creates a fresh instance.

ImageResize extends the Composer Gumlet class and overrides loading for GIF/JPEG/PNG/WebP/AVIF/BMP, with imagecreatefromstring fallback on thrown errors. It then uses inherited resizing/saving behavior.

## Cache and filesystem behavior

The destination is public/m/c/image/<entire option/path string>. Directories are recursively created. A .webp destination forces WebP output. The response sets Content-Type/Length, Last-Modified, and a one-year public max-age, then exits. The nearby "60 days" comment does not match the arithmetic.

Existing generated files may be served directly by the web server, depending on host configuration. The PHP controller itself does not implement source freshness comparison, cache eviction, or conditional request handling.

MediaDir::cleanFilename(string) replaces disallowed basename characters with hyphens. getCleanUniqueFilename(string $destinationPath, string $originalFilename) adds incrementing suffixes while paths exist; it does not reserve the filename atomically.

## Limitations

The route is public and resolves from private storage; there is no authentication, path-containment validation, source allowlist, or size cap in this code. Do not treat private storage as protected when exposed through this endpoint.

The catch block calls dd($message), so the following placeholder render is unreachable. A missing image branch passes only a basename to renderImage instead of reliably selecting the placeholder.

No media upload/gallery subsystem is supplied here. [Theme substitution](../frontend/theme-overrides.md) does not apply to this PHP image reader. A separate older Gumlet copy exists under Liv/Lib/Gumlet, but this Media class extends the Composer dependency.

---
[Assets](../frontend/asset-resolution.md) · [Cache lifecycle](../reference/caching.md) · [Documentation index](../README.md)
