<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MallUser extends Model
{
    protected $table = 'malls_users';
    protected $fillable = [
        'user_id',
    	'mall_id',
    ];

    public function users(){
    	return $this->belongsTo('App\User');
    }

    public function malls(){
    	return $this->belongsTo('App\Mall');
    }
}
