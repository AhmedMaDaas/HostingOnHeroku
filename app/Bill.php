<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';
    protected $fillable = [
    	'user_id',
    	'address',
    	'total_coast',
        'status',
        'payment',
        'visible',
    	'shipping_coast',
        'new',
        'id_payment',
    ];

    public function products(){
    	return $this->hasMany('App\BillProduct');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
