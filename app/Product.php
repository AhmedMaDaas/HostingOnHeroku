<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $fillable = [
        'name_ar',
    	'name_en',
        'photo',
        'optimized_photo',
        'content',
        'department_id',
        'trade_id',
        'manu_id',
        'country_id',
        'color_id',
        'size_id',
        'size',
        'weight_id',
        'product_weight',
        'other_data',
        'stock',
        'start_at',
        'end_at',
        'offer_start_at',
        'offer_end_at',
        'price_offer',
        'price',
        'status',
        'reason',
        'evaluation',
    ];

    public function otherData(){
        return $this->hasMany('App\OtherData');
    }

    public function files(){
        return $this->hasMany('App\File', 'relationId', 'id')->where('fileType', 'product');
    }

    public function malls(){
        return $this->hasMany('App\MallProduct');
    }

    public function sizes(){
        return $this->hasMany('App\SizeProduct');
    }

    public function bills(){
        return $this->hasMany('App\BillProduct');
    }

    public function colors(){
        return $this->hasMany('App\ColorProduct');
    }

    public function department(){
        return $this->belongsTo('App\Department');
    }

    public function commints(){
        return $this->hasMany('App\Commint')->orderBy('id','desc');
    }

    public function manufacturer(){
        return $this->belongsTo('App\Manufacturer');
    }

    public function trade(){
        return $this->belongsTo('App\TradeMark');
    }

    public function weight(){
        return $this->belongsTo('App\Weight');
    }

    public function users(){
        return $this->hasMany('App\ProductUser')->where('user_id',session('login'));
    }

    public function evaluationUsers(){
        return $this->hasMany('App\ProductEvaluation')->where('user_id',session('login'));
    }
}
