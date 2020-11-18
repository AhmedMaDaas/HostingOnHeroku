<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commint extends Model
{
	protected $table = 'commints';
    protected $fillable = [
    'product_id',
    'user_id',
    'commint',
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
