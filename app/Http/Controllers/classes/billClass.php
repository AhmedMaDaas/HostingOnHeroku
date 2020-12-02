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

/*this statments were in checkout but replaced by the function updatePriceInBill
if(($billProduct->product_coast != $billProduct->product->price) && 
              (time()-(60*60*24)) > strtotime($billProduct->product->offer_end_at)){

              // call function from app/http/helper
               updatePriceBill($billProduct->id,$billProduct->product->price,$billProduct->quantity,$bill->id,$billProduct->product_coast,$bill->total_coast);
            }
            if(($billProduct->product_coast != $billProduct->product->price_offer) && 
              (time()-(60*60*24)) <= strtotime($billProduct->product->offer_end_at)){

              // call function from app/http/helper
               updatePriceBill($billProduct->id,$billProduct->product->price_offer,$billProduct->quantity,$bill->id,$billProduct->product_coast,$bill->total_coast);
            }

            */
	function updatePriceInBill($billId,$quantity,$price='price',$ope='<'){
		$currentDate = Carbon::now();

		// $query = "update `bill_products` inner join `bills` 
		// 			on `bills`.`id` = `bill_products`.`bill_id` 
		// 			inner join `products` 
		// 			on `products`.`id` = `bill_products`.`product_id` 
		// 			and `products`.`price` != `bill_products`.`product_coast` 
		// 			 set
		// 			 `bills`.`total_coast` = `bills`.`total_coast` - (`bill_products`.`product_coast` * `bill_products`.`quantity`),
		// 			  `bill_products`.`product_coast` = `products`.`price`,
		// 			  `bills`.`total_coast` = `bills`.`total_coast` + (`bill_products`.`product_coast` * `bill_products`.`quantity`)
		// 			  where `bill_id` = 38 
		// 			  and `products`.`offer_end_at` < ".$currentDate;

		// 			  return \DB::update($query);


		$records = BillProduct::where('bill_id',$billId)
		->join('products',function($join)use($price)
		 {
		   $join->on('products.id', '=', 'bill_products.product_id');
		   $join->on('products.'.$price, '!=', 'bill_products.product_coast');

		 })
		->where('products.offer_end_at',$ope,$currentDate)->get();

		if(count($records)){
			$oldTotal = 0;
			$newTotal = 0;
			foreach ($records as $key => $record) {
				$oldTotal = $oldTotal+($record->product_coast*$record->quantity);
				$newTotal = $newTotal+($record->$price*$record->quantity);
			}
			//dd($oldTotal.','.$newTotal);
			$oldTotal = $quantity - $oldTotal;
			$newTotal = $oldTotal + $newTotal;
			

			$records = BillProduct::where('bill_id',$billId)
			->join('products',function($join)use($price)
			 {
			   $join->on('products.id', '=', 'bill_products.product_id');
			   $join->on('products.'.$price, '!=', 'bill_products.product_coast');

			 })
			->where('products.offer_end_at',$ope,$currentDate)
			->update(['bill_products.product_coast'=>\DB::raw("`products`.`".$price."`")]);

			Bill::where('id',$billId)->update(['total_coast'=>$newTotal]);
		}
		
		//dd($records);
		return $records;
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


	function checkQuantityInBill($billId){
		$array = [];

		$elements = BillProduct::where('bill_id',$billId)
		->join('products',function($join)
		 {
		   $join->on('products.id', '=', 'bill_products.product_id');
		   $join->on('products.stock', '<', 'bill_products.quantity');

		 })->leftJoin('color_products',function($join)
		 {
		   $join->on('color_products.product_id', '=', 'bill_products.product_id');
		   $join->on('color_products.color_id', '=', 'bill_products.color_id');
		   $join->on('color_products.quantity', '<', 'bill_products.quantity');

		 })->leftJoin('size_products',function($join)
		 {
		   $join->on('size_products.product_id', '=', 'bill_products.product_id');
		   $join->on('size_products.size_id', '=', 'bill_products.size_id');
		   $join->on('size_products.quantity', '<', 'bill_products.quantity');

		 })
		 ->select('bill_products.*','color_products.quantity as color_quantity','color_products.product_id as color_product',
		 	'size_products.quantity as size_quantity','size_products.product_id as size_product','products.*')
		 ->get();
		 
		 $message = $this->createMessage($elements);

		 if(!$message){
		 	$array['status']=false;
		 	$array['msg']='';
		 }else{

		 	$array['status']=true;
		 	$array['msg']=$message;
		 }
		   
		 return $array;
		//return $elements;
	}

	function createMessage($elements){
		if(!empty($elements)){
			$str ='';
			$productQuantity = 0;
			foreach ($elements as $key => $element) {
				if(empty($element->color_quantity) && empty($element->size_quantity)) $productQuantity = $element->stock;
				elseif($element->color_quantity > $element->size_quantity) $productQuantity = $element->color_quantity;
				else $productQuantity = $element->size_quantity;
				$str = $str.'(quantity of product : ['.$element->name_en.' | '.$element->name_ar.'] still just '.$productQuantity.' ) ';
			}

			return $str;
		}
		return false;
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
