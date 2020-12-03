<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';
    protected $fillable = [
    	'name_ar',
        'name_en',
        'description',
        'photo',
        'icon',
        'keywords',
        'parent',
        'owner',
        'is_active'
    ];

    public function sizes(){
    	return $this->hasMany('App\Size');
    }

    public function products(){
        return $this->hasMany('App\Product');
    }

    public function malls(){
        return $this->hasMany('App\MallDepartment');
    }

    public function ads(){
        return $this->hasMany('App\AdDepartment');
    }

    public function child(){
       return $this->hasMany('App\Department', 'parent');
    }
}
