<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherData extends Model
{
    protected $table = 'other_datas';
    protected $fillable = [
    	'product_id',
		'row',
		'column',
		'text',
		'rowspan',
		'colspan',
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
