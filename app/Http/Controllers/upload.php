<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Storage;

class Upload extends Controller
{
    public static function upload($data = []){

    	$data['fileName'] = isset($data['fileName']) ? $data['fileName'] : time();

    	if(request()->hasFile($data['file']) && $data['uploadType'] == 'single'){
            
            $destinationPath = 'storage/' . $data['path'];
            $imageName = 'img_' . rand(1000000, 9999999) . '.' . request()->file($data['file'])->getClientOriginalExtension();
            request()->file($data['file'])->move($destinationPath, $imageName);
            $path = $data['path'] . '/' . $imageName;
    		return $path;
    	}
    	else if( request()->hasFile($data['file']) && $data['uploadType'] == 'multiple' ){
    		$file = request()->file($data['file']);

    		$name = $file->getClientOriginalName();
    		$type = $file->getMimeType();
    		$size = $file->getSize();
    		$hashName = $file->hashName();

    		$destinationPath = 'storage/' . $data['path'];
            $imageName = 'img_' . rand(1000000, 9999999) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $imageName);
            $path = $data['path'] . '/' . $imageName;

    		$add = File::create([
    			'name' => $name,
		        'size' => $size,
		        'file' => $hashName,
		        'fullFile' => $path,
		        'mimeType' => $type,
		        'fileType' => $data['fileType'],
		        'relationId' => $data['relationId'],
	        ]);

	        return $add->id;
    	}
    }
}
