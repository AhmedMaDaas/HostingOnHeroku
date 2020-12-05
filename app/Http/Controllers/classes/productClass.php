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
use App\ColorProduct;
use App\SizeProduct;
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
		$product = Product::where('id',$productId)->where('stock','>=',1)->with('malls')->with('files')
		->with(['sizes'=>function($query){
			$query->where('quantity','>=',1);
		}])->with(['colors'=>function($query){
			$query->where('quantity','>=',1);
		}])->first();
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

		$relatedProducts = Product::where('stock','>=',1)->whereHas('malls',function($query) use($mallId){
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
		$product = Product::where('id',$productId)->where('stock','>=',1)->first();
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

	function checkQuantity($productId,$quantity,$sizeId,$colorId){


		$product = Product::where('id',$productId)->where('stock','>=',$quantity)->first();
		if(empty($product))return false;
		if($colorId != -1){
			$product = ColorProduct::where(['product_id'=>$productId,'color_id'=>$colorId])->where('quantity','>=',$quantity)->first();
			if(empty($product))return false;
		}
		if($sizeId != -1){
			$product = SizeProduct::where(['product_id'=>$productId,'size_id'=>$sizeId])->where('quantity','>=',$quantity)->first();
			if(empty($product))return false;
		}

		return true;
	}

	

}

?>