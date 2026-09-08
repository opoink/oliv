<?php
use Illuminate\Support\Facades\Route;

use \Plugins\Opoink\Media\Http\Controllers\Client\MediaCacheImage AS ClientControllerMediaCacheImage;

Route::get('/m/c/image/{path}', [ClientControllerMediaCacheImage::class, 'renderImage'])->where('path', '.*');
?>