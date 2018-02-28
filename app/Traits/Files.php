<?php
namespace App\Traits;
 
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;
 
trait Files {

    /*upload single file image*/
    function singleFileImage($path,$file,$sizeMediumWidth=400,$sizeMediumHeight=400,$sizeSmallWidth=100,$sizeSmallHeight=100){
        
        $filename = str_random(10).".".$file->getClientOriginalExtension();
        $uploadPath = $path . "/" ;
        $uploadMediumPath = $path . "/medium/";
        $uploadSmallPath = $path . "/small/" ;
        if(!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
        if(!File::exists($uploadMediumPath)) {
            File::makeDirectory($uploadMediumPath, 0775, true, true);
        }
        if(!File::exists($uploadSmallPath)) {
            File::makeDirectory($uploadSmallPath, 0775, true, true);
        }
        $small=Image::make(File::get($file))->save($uploadPath. $filename);
        $medium=Image::make(File::get($file))->fit($sizeMediumWidth, $sizeMediumHeight)->save($uploadMediumPath. $filename);
        $small=Image::make(File::get($file))->fit($sizeSmallWidth, $sizeSmallHeight)->save($uploadSmallPath. $filename);
        return $filename;
        
    }
    /*upload single file*/
    function singleFile($path,$file){
        $filename = str_random(5) .'-'. str_slug($file->getClientOriginalName(), '-') . '.' . $file->getClientOriginalExtension();
        $uploadPath = $path . "/";
        if(!File::exists($path)) {
            File::makeDirectory($uploadPath, 0775, true, true);
        }
       
        $file->move($uploadPath,$filename);

        return $filename;
    }
    
    
 }