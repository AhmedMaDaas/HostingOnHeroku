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
    		Storage::has($data['deleteFile']) ? Storage::delete($data['deleteFile']) : '';
            $destinationPath = 'storage/' . $data['path'];
            $imageName = 'img_' . rand(1000000, 9999999) . '.' . $request->file($data['file'])->getClientOriginalExtension();
            $request->file($data['file'])->move($destinationPath, $imageName);
    		return $destinationPath . '/' . $imageName;
    	}
    	else if( request()->hasFile($data['file']) && $data['uploadType'] == 'multiple' ){
    		$file = request()->file($data['file']);

    		$name = $file->getClientOriginalName();
    		$type = $file->getMimeType();
    		$size = $file->getSize();
    		$hashName = $file->hashName();

    		$file->store($data['path']);

    		$add = File::create([
    			'name' => $name,
		        'size' => $size,
		        'file' => $hashName,
		        'fullFile' => $data['path'] . '/' . $hashName,
		        'mimeType' => $type,
		        'fileType' => $data['fileType'],
		        'relationId' => $data['relationId'],
	        ]);

	        return $add->id;
    	}
    }
}
