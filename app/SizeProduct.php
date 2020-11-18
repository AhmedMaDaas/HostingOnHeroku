<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeProduct extends Model
{
    protected $table = 'size_products';
    protected $fillable = [
    	'product_id',
    	'size_id',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function size(){
        return $this->belongsTo('App\Size');
    }
}
