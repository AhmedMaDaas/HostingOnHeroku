<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductEvaluation extends Model
{
    protected $table = 'products_evaluation';
    protected $fillable = [
        'user_id',
    	'product_id',
    	'evaluation',
    ];

    public function users(){
    	return $this->belongsTo('App\User');
    }

    public function products(){
    	return $this->belongsTo('App\Product');
    }
}
