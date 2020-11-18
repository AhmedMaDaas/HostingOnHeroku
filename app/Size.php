<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    //
    protected $table = 'sizes';
    protected $fillable = [
    	'name_ar',
        'name_en',
        'is_public',
        'department_id',
        'owner',
    ];

    public function department(){
    	return $this->belongsTo('App\Department');
    }

    public function products(){
        return $this->hasMany('App\SizeProduct');
    }

    public function productsInBill(){
        return $this->hasMany('App\BillProduct');
    }
}
