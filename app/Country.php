<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $table = 'countries';
    protected $fillable = ['name_ar','name_en','mob','code','logo', 'currency'];

    public function cities(){
    	return $this->hasMany('App\City');
    }

    public function malls(){
        return $this->hasMany('App\Mall');
    }
}
