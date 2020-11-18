<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
    protected $table = 'states';
    protected $fillable = [
    	'name_ar',
        'name_en',
        'country_id',
        'city_id',
    ];

    public function country(){
    	return $this->belongsTo('App\Country');
    }

    public function city(){
    	return $this->belongsTo('App\City');
    }
}
