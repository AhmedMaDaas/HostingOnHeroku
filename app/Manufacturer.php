<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    //
    protected $table = 'manufacturers';
    protected $fillable = [
    	'name_ar',
        'name_en',
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
        'owner',
    ];
}
