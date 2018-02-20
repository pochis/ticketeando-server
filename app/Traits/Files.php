<?php
namespace App\Traits;
 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
 
trait Files {

    /*upload single file*/
    function singleFile($path, $file,$sizeMediumWidth=400,$sizeMediumHeight=400,$sizeSmallWidth=100,$sizeSmallHeight=100){
        
        $filename = str_random(10).".".$file->getClientOriginalExtension();
        $uploadPath = $path . "/" . $filename;
        $uploadMediumPath = $path . "/medium/" . $filename;
        $uploadSmallPath = $path . "/small/" . $filename;
        Storage::disk('public')->put($uploadPath, File::get($file));
        $medium=Image::make(Storage::disk('public')->get($uploadPath))->fit($sizeMediumWidth, $sizeMediumHeight)->stream();
        $small=Image::make(Storage::disk('public')->get($uploadPath))->fit($sizeSmallWidth, $sizeSmallHeight)->stream();
        Storage::disk('public')->put($uploadMediumPath,$medium);
        Storage::disk('public')->put($uploadSmallPath,$small);
        return $filename;
        
    }     
    
    
    
 }