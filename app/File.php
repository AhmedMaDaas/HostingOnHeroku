<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $table = 'files';
    protected $fillable = [
    	'name',
        'size',
        'file',
        'fullFile',
        'mimeType',
        'fileType',
        'relationId',
    ];

    public function product(){
        return $this->belongsTo('App\Product', 'relationId', 'id')->where('fileType', 'product');
    }
}
