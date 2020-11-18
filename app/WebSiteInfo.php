<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebSiteInfo extends Model
{
    protected $table = 'web_site_info';
    protected $fillable = [
    	'main_photo',
		'photo_title',
		'photo_desc',
		'web_desc',
		'desc_photo',
    ];

    public function attrInfo(){
        return $this->hasMany('App\AttractiveInformation');
    }
}
