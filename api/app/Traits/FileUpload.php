<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\File;

trait FileUpload
{
    protected function saveImages(Request $request, $file, $folder, $width = null, $height = null)
    {
        $path = public_path() . '/' . $folder;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $name = base64_encode(rand(1000, 9999) . time()) . '.' . explode('/', explode(':', substr($request->$file, 0, strpos($request->$file, ';')))[1])[1];
        Image::make($request->$file)->resize($width, $height)->save($path . '/' . $name);
        return $folder . $name;
    }

    protected function saveImagesBin(Request $request, $file, $folder, $width = null, $height = null)
    {
        $path = public_path() . '/' . $folder;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $name = base64_encode(rand(1000, 9999) . time()) . '-' . $request->$file->getClientOriginalName();
        Image::make($request->$file)->resize($width, $height)->save($path . '/' . $name);
        return $folder . $name;
    }

    protected function saveImagesVue($request, $file, $folder, $width = null, $height = null)
    {
        $path = public_path() . '/' . $folder;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name = base64_encode(rand(1000, 9999) . time()) . '.' . explode('/', explode(':', substr($request[$file], 0, strpos($request[$file], ';')))[1])[1];
        Image::make($request[$file])->resize($width, $height)->save($path . '/' . $name);
        return $folder . $name;
    }

    protected function saveImagesVue2($request, $folder)
    {
        $path = public_path() . '/' . $folder;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $name = base64_encode(rand(1000, 9999) . time()) . '.' . explode('/', explode(':', substr($request, 0, strpos($request, ';')))[1])[1];
        Image::make($request)->save($path . '/' . $name);
        return $folder . $name;
    }

    /*
     * method for remove file
     * */
    protected function removeImage($path){
        if(file_exists(public_path($path))){
            unlink(public_path($path));
        }
    }

    /*
     * method for image upload
     * */
    protected function imageUpload($image,$folder, $width=null, $height=null): string
    {
        $path = public_path() . '/' . $folder;
        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $name = base64_encode(rand(1000, 9999) . time()) . '-' . $image->getClientOriginalName();
        $img2='/'.$name;
        Image::make($image)->resize($width, $height)->save($path . '/' . $name);
        return $folder . $img2;
    }

}
