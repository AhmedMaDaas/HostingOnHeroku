<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillProduct extends Model
{
    protected $table = 'bill_products';
    protected $fillable = [
        'product_id',
        'bill_id',
        'mall_id',
        'quantity',
        'product_coast',
        'size_id',
        'color_id',
    ];

    public function bill(){
        return $this->belongsTo('App\Bill');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function mall(){
        return $this->belongsTo('App\Mall');
    }

    public function size(){
        return $this->belongsTo('App\Size');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }
}
