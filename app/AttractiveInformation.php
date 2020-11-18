<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttractiveInformation extends Model
{
	protected $table = 'attractive_informations';
	protected $fillable = [
		'photo',
		'title',
		'web_site_info_id',
	];

    public function websiteInfo(){
    	return $this->belongsTo('App\WebSiteInfo');
    }
}
