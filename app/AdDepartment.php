<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdDepartment extends Model
{
    protected $table = 'ad_departments';
    protected $fillable = [
    	'ad_id',
    	'department_id',
    ];

    public function ad(){
    	return $this->belongsTo('App\Ad');
    }

    public function department(){
    	return $this->belongsTo('App\Department');
    }
}
