<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mall extends Model
{
    //
    protected $table = 'malls';
    protected $fillable = [
    	'name_ar',
        'name_en',
        'country_id',
        'user_id',
        'facebook',
        'twitter',
        'website',
        'contact_name',
        'lat',
        'lng',
        'icon',
        'mobile',
        'email',
        'address',
        'followers',
    ];

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function products(){
        return $this->hasMany('App\MallProduct');
    }

    public function billProducts(){
        return $this->hasMany('App\BillProduct');
    }

    public function departments(){
        return $this->hasMany('App\MallDepartment');
    }

    public function users(){
        return $this->hasMany('App\MallUser')->where('user_id',session('login'));
    }
}
