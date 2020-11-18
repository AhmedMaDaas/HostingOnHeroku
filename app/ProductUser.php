<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUser extends Model
{
    protected $table = 'products_users';
    protected $fillable = [
        'user_id',
    	'product_id',
    ];

    public function users(){
    	return $this->belongsTo('App\User');
    }

    public function products(){
    	return $this->belongsTo('App\Product');
    }
}
