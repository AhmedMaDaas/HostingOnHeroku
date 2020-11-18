<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table = 'ads';
    protected $fillable = [
    	'start_at',
    	'end_at',
    	'ad',
    	'photo',
    	'title_ar',
        'title_en',
    	'mall_id',
        'discount',
    ];

    public function products(){
    	return $this->hasMany('App\AdProduct');
    }

    public function departments(){
        return $this->hasMany('App\AdDepartment');
    }

    public function mall(){
    	return $this->belongsTo('App\Mall');
    }
}
