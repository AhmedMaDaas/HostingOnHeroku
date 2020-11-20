<?php

namespace App\Http\Controllers\Classes;
use App\Product;
use App\File;
use App\Department;
use App\Ad;
use App\User;
use App\Bill;
use App\BillProduct;
use App\ColorProduct;
use App\SizeProduct;
use \Carbon\Carbon;

class billClass{
	

	function __construct()
	{
		
	}

	function checkBill($userId){
		$billUser = Bill::whereUser_id($userId)->where('status','opened')->with(['products' => function($query){
			 $query->orderBy('mall_id','asc');
		}])->orderBy('created_at', 'desc')->first();
		if(empty($billUser) || $billUser->status!='opened' ){
			return false;
		}else{
			//$products = $billUser->products();
			//dd($billUser);
			return $billUser;
            
		}
	}

//not completed yet
	function updatePriceInBill($billId){
		$currentDate = Carbon::now();

		$test = BillProduct::where('bill_id',$billId)
		->join('products', 'bill_products.product_id', '=', 'products.id')
		->where('products.offer_end_at','<',$currentDate)->get();
		//->where('bill_products.product_coast','=','products.price')->get();

		// foreach ($test as $key => $record) {
		// 	if($record->product_coast != $record->price)
		// 		BillProduct::where('product_id',$record->product_id)->update(['product_coast'=>$record->price]);
		// }
		return $test;
	}

	function getMallsIds($billProduct){
		$mallsIds = $billProduct->pluck('mall_id')->toArray();
		$mallsIds = array_unique($mallsIds);
		return $mallsIds;
	}

	function checkType($type){
		if($type == 'plus' || $type == 'minus')return $type;
		//elseif( $type = 'minus')return [$type,0];
		return 'plus';
	}

	function checkColorId($colorId,$productId){
		$color = ColorProduct::where(['product_id' => $productId,'color_id' => $colorId])->where('quantity','>',0)->first();
		if(empty($color)){
			$color = ColorProduct::where('product_id',$productId)->first();
			if(empty($color))return -1;
			return false;
		}
		return $colorId;

	}

	function checkSizeId($sizeId,$productId){
		$size = SizeProduct::where(['product_id' => $productId,'size_id' => $sizeId])->where('quantity','>',0)->first();
		if(empty($size)){
			$size = SizeProduct::where('product_id',$productId)->first();
			if(empty($size))return -1;
			return false;
		}
		return $sizeId;
	}

	function getProductQuantity($productId){

	}

	function createBill($userId ,$productPrice,$productId,$colorId,$sizeId,$mallId,$quantity){
		$totalPrice = $productPrice*$quantity;
		$bill = Bill::create(['user_id'=>$userId,'address'=>'damas','status'=>'opened','total_coast'=>$totalPrice]);
		$billProduct = BillProduct::create(['bill_id'=>$bill->id,'product_id'=>$productId,'color_id'=>$colorId,'size_id'=>$sizeId,'mall_id'=>$mallId,'product_coast'=>$productPrice,'quantity'=>$quantity]);
		return $bill;
	}

	// function deleteProductBill($productId,$colorId,$sizeId){
	// 	$userId = session('login');
	// 	$billUser = Bill::whereUser_id($userId)->orderBy('created_at', 'desc')->first();
	// 	if(empty($billUser) || $billUser->status!='opened' ){
	// 		return false;
	// 	}else{
	// 		$billProduct = BillProduct::where(['product_id' => $productId,'bill_id'=>$billUser->id,'color_id'=>$colorId,'size_id'=>$sizeId])->first();

 //            if(!empty($billProduct)){
 //            	$billProduct->delete();
 //            	return true;
 //            }else{

 //            	return false;
 //            }
            
	// 	}

	// }

	// function getLastBills($userId){
	// 	$billsIds = Bill::whereUser_id($userId)->orderBy('created_at', 'desc')->take(4)->pluck('id')->toArray();
	// 	$billsIds = array_unique($billsIds);
		
	// 	return $billsIds;
	// 	// if(count($billsIds) ){
	// 	// 	return $billsIds;
	// 	// }else{

	// 	// 	return false;
	// 	// }
	// }

}

?>
