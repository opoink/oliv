# Bundled Liv image library

Implementation: src/resources/plugins/Opoink/Liv/Lib/Gumlet/ImageResize.php and ImageResizeException.php.

This is a separate Gumlet-derived class under **Plugins\Opoink\Liv\Lib\Gumlet**. [Media](media.md) extends the Composer **Gumlet\ImageResize** instead. No active caller of this Liv copy was found in supplied OLIV code; external plugins may use it. PHP 5/7 compatibility branches remain despite OLIV's PHP ^8.3 constraint.

## Public API

Methods have no declared return types. Transformation/save methods generally return this.

| API | Behavior |
| --- | --- |
| __construct($filename) | Loads GD image from file/data URI; GIF/JPEG/PNG/WebP/BMP |
| createFromString($image_data) | Static Base64-data-URI factory; rejects empty input |
| imageCreateJpegfromExif($filename) | JPEG loading plus EXIF orientation/flip |
| resize($width,$height,$allow_enlarge=false) | Sets dimensions; without enlargement, either oversized dimension resets both to source |
| resizeToWidth / resizeToHeight($dimension,$allow_enlarge=false) | Proportional resize |
| resizeToShortSide / resizeToLongSide($dimension,$allow_enlarge=false) | Proportional side limit |
| resizeToBestFit($max_width,$max_height,$allow_enlarge=false) | Bounding-box fit |
| scale($scale) | Percentage resize with enlargement allowed |
| crop($width,$height,$allow_enlarge=false,$position=self::CROPCENTER) | Aspect-aware positional crop |
| freecrop($width,$height,$x=false,$y=false) | Explicit source origin; falls back to crop if either origin is false |
| getSourceWidth/Height(), getDestWidth/Height() | Dimension getters |
| addFilter(callable $filter) | Adds destination-image filter callback |
| gamma($enable=false) | Toggles gamma correction |
| save($filename,$image_type=null,$quality=null,$permissions=null,$exact_size=false) | Resamples, filters, encodes and optionally chmods |
| getImageAsString($image_type=null,$quality=null) | Temporary-file save/read/delete, returning bytes |
| __toString() | Calls getImageAsString |
| output($image_type=null,$quality=null) | Emits Content-Type and image bytes |

save's exact_size accepts a width/height array despite its boolean-oriented comment. Placement depends on source orientation. Filters receive the destination image and a filter-type argument defaulting to IMG_FILTER_NEGATE.

Defaults: JPEG/WebP quality 85, PNG quality 6, truecolor true, gamma false, interlace 1. Crop constants: top=1, center/centre=2, bottom=3, left=4, right=5, top-center=6. Top-center uses one-quarter of the excess offset.

~~~php
$image = new \Plugins\Opoink\Liv\Lib\Gumlet\ImageResize($sourcePath);
$image->resizeToBestFit(640, 480)->save($destinationPath);
~~~

Use trusted existing source/destination paths. This is a direct image/file API, not a Laravel response, disk wrapper, upload validator or cache manager. Do not conflate its format support/defaults with the Composer version used by Media.

---
[Media](media.md) · [Source coverage](../reference/directory-structure.md) · [Documentation index](../README.md)
