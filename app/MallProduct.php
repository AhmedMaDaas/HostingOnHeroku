<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MallProduct extends Model
{
    protected $table = 'mall_products';
    protected $fillable = [
    	'product_id',
    	'mall_id'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function mall(){
        return $this->belongsTo('App\Mall');
    }
}
