<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'level', 'photo', 'phone', 'facebook_id', 'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shippings(){
        return $this->hasMany('App\Shipping');
    }

    public function malls(){
        return $this->hasMany('App\Mall');
    }

    public function bills(){
        return $this->hasMany('App\Bill');
    }

    public function billsPerMonth(){
        return $this->hasMany('App\Bill')->whereYear('created_at', Carbon::now()->year)
                                         ->whereMonth('created_at', Carbon::now()->month);
    }

    public function statusBills($status){
        return $this->hasMany('App\Bill')->where('status', $status);
    }

    public function notifications(){
        return $this->hasMany('App\Notification', 'owner_id', 'id')->where('relation', '!=', 'admin');
    }

    public function commints(){
        return $this->hasMany('App\Commint')->orderBy('id','desc');
    }

    public function products(){
        return $this->hasMany('App\ProductUser');
    }

    public function evaluationProducts(){
        return $this->hasMany('App\ProductEvaluation');
    }
}
