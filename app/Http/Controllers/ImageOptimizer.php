<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Image;

class ImageOptimizer
{
    public static function storeImage($file, $path, $deleteOld) {

      // Get filename with extension
      $filenameWithExt = $file->getClientOriginalName();

      // Get file path
      $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      // Remove unwanted characters
      $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
      $filename = preg_replace("/\s+/", '-', $filename);

      // Get the original image extension
      $extension = $file->getClientOriginalExtension();

      // Create unique file name
      $fileNameToStore = $filename.'_'.time().'.'.$extension;

      // Refer image to method resizeImage
      $save = Self::resizeImage($file, $fileNameToStore, $path, $deleteOld);

      return $save;
    }

    /**
     * Resizes a image using the InterventionImage package.
     *
     * @param object $file
     * @param string $fileNameToStore
     * @author Niklas Fandrich
     * @return bool
     */
    public static function resizeImage($file, $fileNameToStore, $path, $deleteOld) {
      Storage::has($deleteOld) ? Storage::delete($deleteOld) : '';
      // Resize image
      $resize = Image::make($file)->resize(600, null, function ($constraint) {
        $constraint->aspectRatio();
      })->encode('jpg');

      // Create hash value
      $hash = md5($resize->__toString());

      // Prepare qualified image name
      $image = $hash . "jpg";

      // Put image to storage
      $save = Storage::put($path . "/{$fileNameToStore}", $resize->__toString());

      return $path . '/' . $fileNameToStore;
    }

}