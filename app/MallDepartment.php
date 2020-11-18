<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MallDepartment extends Model
{
    protected $table = 'mall_departments';
    protected $fillable = [
        'mall_id',
        'department_id',
    ];

    public function mall(){
    	return $this->belongsTo('App\Mall');
    }

   public function department(){
    	return $this->belongsTo('App\Department');
    }
}
