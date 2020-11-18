<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Product;


class CategoriesController extends Controller
{
    public function relatedProducts($category){

        if($category === 'best-selling'){
            return json_encode($this->bestSellingProducts());
        }

        elseif($category === 'for-you'){
            return json_encode($this->forYouProducts());
        }

        elseif($category === 'offer-products'){
            return json_encode($this->offerProducts());
        }

        return json_encode([]);
    }

    private function bestSellingProducts(){
        $products = Product::orderBy('id', 'desc')->with(['department'])->get();
        return $products;
    }

    private function offerProducts(){
        $products = Product::orderBy('id', 'desc')->with(['department'])
        ->where([['status', 'active'], ['price_offer', '!=', null], ['offer_start_at', '<=', Carbon::now()->toDateString()], ['offer_end_at', '>=', Carbon::now()->toDateString()]])->get();
        return $products;
    }

    private function forYouProducts(){
        $products = Product::orderBy('id', 'desc')->with(['department'])->get();
        return $products;
    }
}
