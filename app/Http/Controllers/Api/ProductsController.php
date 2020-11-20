<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Product;


class ProductsController extends Controller
{
    public function productDetails($id){
        $product = Product::where('id', $id)->with(['files', 'colors' => function($query){
            return $query->with('color');
        }, 'sizes' => function($query){
            return $query->with('size');
        }, 'malls' => function($query){
            return $query->with(['mall' => function($query){
                return $query->with('country');
            }]);
        }, 'weight', 'department'])->first();
        return json_encode($product);
    }
}
