<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    //
    protected $table = 'colors';
    protected $fillable = [
    	'name_ar',
    	'name_en',
    	'color',
    	'owner',
    ];

    public function products(){
        return $this->hasMany('App\ColorProduct');
    }

    public function productsInBill(){
        return $this->hasMany('App\BillProduct');
    }
}
