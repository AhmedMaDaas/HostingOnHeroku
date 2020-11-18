<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorProduct extends Model
{
    protected $table = 'color_products';
    protected $fillable = [
    	'product_id',
    	'color_id',
        'quantity',
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }
}
