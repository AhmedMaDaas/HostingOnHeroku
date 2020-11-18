<?php

namespace App\Http\Controllers\Classes;
use App\Product;
use App\File;
use App\Department;
use App\Ad;
use App\User;
use App\Bill;
use App\Mall;
use App\BillProduct;
use App\MallProduct;
use App\Commint;

class productClass{
	

	function __construct()
	{
		
	}

	function getMallInfo($productId){
		

		$mall = MallProduct::where('product_id',$productId)->with('mall')->first();
		
		if(!empty($mall)){
			return $mall;
		}
		return false;
	}

	function getProduct($productId,$mallId){
		//$numOfProduct = Settings::where('name')
		$product = Product::where('id',$productId)->where('stock','>=',1)->with('malls')->with('files')->with('sizes')->with('colors')->first();
		if(!empty($product)){
			return $product;
		}
		return false;
	}

	function getCommints($productId,$commintsInPage){
		$commints = Commint::where('product_id',$productId)->orderBy('created_at','desc')->paginate($commintsInPage);
		//$commints = Product::find($productId)->commints()->paginate(1);
		return $commints;
	}

	function getSomeProducts($mallId,$productId){

		$relatedProducts = Product::where('id',$productId)->where('stock','>=',1)->whereHas('malls',function($query) use($mallId){
					return $query->where('mall_id',$mallId);
				})->inRandomOrder()->take(9)->get();
		return $relatedProducts;

		// $relatedProducts = $mall->load(['products'=> function($query)use($productId){
		// 		$query->where('id','!=',$productId)
		// 			  ->where('stock','>=',1);
		// }])->inRandomOrder()->take(9)->get();
		// return $relatedProducts;
		
	}

	function checkMallId($productId,$mallId){
		$mall = Mall::where('id',$mallId)->whereHas('products.Product', function ($query) use($productId) {
	    	return $query->where('id', '=', $productId);
			})->first();

			if(!empty($mall)){
				return $mallId;
			}
			return false;
	}

	function checkProduct($productId){
		$product = Product::find($productId)->where('stock','>=',1);
		if(!empty($product))return $product;
		return false;
	}

	function checkPriceProduct($product){
		//$product = $product->first();
		if(!empty($product->price_offer) && (time()-(60*60*24)) <= strtotime($product->offer_end_at))
				return $product->price_offer;
				//return $product->price - $product->price_offer;
			return $product->price;
	}

	

}

?>