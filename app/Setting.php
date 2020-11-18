<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $table = 'settings';
    protected $fillable =[
        'sitename_en',
        'logo',
        'icon',
        'email',
        'main_lang',
        'describtion',
        'keywords',
        'status',
        'message_maintenance',
        'sitename_ar'
    ];
}
