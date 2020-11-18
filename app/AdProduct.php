<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdProduct extends Model
{
    protected $table = 'ad_products';
    protected $fillable = [
        'ad_id',
        'product_id',
    ];

    public function product(){
    	return $this->belongsTo('App\product');
    }


   public function ad(){
    	return $this->belongsTo('App\ad');
    }
}
