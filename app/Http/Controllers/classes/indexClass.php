<?php

namespace App\Http\Controllers\Classes;
use App\Product;
use App\File;
use App\Department;
use App\Ad;
use App\User;
use App\Bill;
use App\BillProduct;
use App\Mall;
use App\MallUser;
use App\ProductUser;
use App\ProductEvaluation;
use App\SizeProduct;
use App\ColorProduct;
use App\MallProduct;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class indexClass{
	

	function __construct()
	{
		
	}


	function getProducts($all = false , $skip = 0){
		//$numOfProduct = Settings::where('name')
		if(!$all)
			$products = Product::where('stock','>=',1)->inRandomOrder()->with('malls')->with('users')->take(6)->get();
		else $products = Product::where('stock','>=',1)->inRandomOrder()->with('malls')->with('users')->skip($skip)->take(6)->get(); 
		return $products;
	}


	function getVideoUrl(){
		$videoInfo = File::whereFiletype('video')->first();//dd($videoInfo);
		return $videoInfo->fullFile;
	}

	function getDepartmentsWithParent(){
		$allDep = [];
		$mainDep = [];

		$mainDepartments = \DB::table('departments')
                ->select(
                    'departments.*'
                )
                ->whereNotExists( function ($query) {
                    $query->select(DB::raw(1))
                    ->from('products')
                    ->whereRaw('departments.id = products.department_id');
                })
                ->get();
		$subDepartments = Department::whereHas('malls.mall.products.product',function($query){
					return $query->whereNotNull('id');
			})->get();

		// $mainDepartments = Department::whereNull('parent')->get();
		// $subDepartments = Department::whereNotNull('parent')->get();
		// $allDep[] = array_merge($allDep,[1,2]);
		// dd($allDep);
		foreach ($mainDepartments as $key => $main) {
			$allDep[$main->id] = [];
			$mainDep[$main->id] = $main;
			foreach ($subDepartments as $key => $sub) {
				if($sub->parent == $main->id){
					$allDep[$main->id] = array_merge($allDep[$main->id],[$sub]);
				}
			}
			
		}
		//dd($allDep);
		return [$allDep,$mainDep];
	}

	function getDepartments(){
		// $departments = Department::whereHas('malls.mall.products.product',function($query){
		// 			return $query->whereNotNull('id');
		// 	})->get();

		// $deps = \DB::table('departments')
  //               ->select(
  //                   'departments.*'
  //               )
  //               ->whereNotExists( function ($query) {
  //                   $query->select(DB::raw(1))
  //                   ->from('products')
  //                   ->whereRaw('departments.id = products.department_id');
  //               })
  //               ->get();
  //   	return $deps;

		// $departments = Department::wheredoesnthave('malls.mall.products.product',function($query){
		// 			return $query->whereNotNull('id');
		// 	})->get();

		$departments = Department::all();
		return $departments;
	}

	function getAds(){
		$currentDate = Carbon::now();
		//dd($date->toDateTimeString());
		
		$ads = Ad::where('end_at','>=',$currentDate)->inRandomOrder()->with(['products.product'=>function($query){
			$query->where('stock','>=',1);
		}])->with('mall')->take(5)->get();
		return $ads;
	}


	function checkLogin(){
		if(session('login')){
			$userId = session('login');
			$billUser = Bill::whereUser_id($userId)->where('status','opened')->orderBy('created_at', 'desc')->first();
			if(!empty($billUser) && $billUser->status=='opened' ){
				$sumQuantity = $this->getAllQuantity($billUser);
				return ['total_coast'=>$billUser->total_coast ,'sumQuantity'=>$sumQuantity];
			}
		}
		return['total_coast' => 00.00 , 'sumQuantity' => 0];
	}

	function getPrice($productId){
		$product = Product::where('id',$productId)->where('stock','>=',1)->first();
		if(!empty($product)){
			if(!empty($product->price_offer) && (time()-(60*60*24)) <= strtotime($product->offer_end_at))
				return $product->price_offer;
				//return $product->price - $product->price_offer;
			return $product->price;
		}
		return false;
	}

	function checkProductSizeColor($productId,$colorId,$sizeId){

	}

	function sumPrice($productId,$colorId,$sizeId,$mallId,$quantity,$type = 'plus'){
		//return [$type,$type,$type];
		if(session('login')){
			$userId = session('login');
			$user = User::where('id',$userId)->first();
			$price = $this->getPrice($productId);
			if($price == false)return false;
			$sumQuantityAndTotalCost = $this->createBill($user,$price,$productId,$colorId,$sizeId,$mallId,$quantity,$type);
			return $sumQuantityAndTotalCost;
		}else{
			return 'login';
		}
	}

	function createBill($user,$price,$productId,$colorId,$sizeId,$mallId,$quantity,$type){
		$billUser = Bill::whereUser_id($user->id)->where('status','opened')->orderBy('created_at', 'desc')->first();
		$priceQuantity = $price * $quantity;
		if(empty($billUser) || $billUser->status!='opened' ){
			$bill = Bill::create(['user_id'=>$user->id,'address'=>'damas','status'=>'opened','total_coast'=>$priceQuantity]);
			BillProduct::create(['bill_id'=>$bill->id,'product_id'=>$productId,'color_id'=>$colorId,'size_id'=>$sizeId,'mall_id'=>$mallId,'product_coast'=>$price,'quantity'=>$quantity]);
			return [$bill->total_coast,$quantity];
		}else{
			if($type == 'plus')$total = $billUser->total_coast + $priceQuantity;
			else $total = $billUser->total_coast - $priceQuantity;
			$billUser->update(['total_coast' => $total]);
            $productQuantity = $this->increaseQuantityProductInBill($productId,$billUser,$colorId,$sizeId,$mallId,$price,$quantity,$type);
            $sumQuantity = $this->getAllQuantity($billUser);

            return [$billUser->total_coast ,$sumQuantity , $productQuantity];
            
		}
	}

	function increaseQuantityProductInBill($productId,$billUser,$colorId,$sizeId,$mallId,$price,$quantity,$type){
		$billProduct = BillProduct::where(['product_id' => $productId,'bill_id'=>$billUser->id,'color_id'=>$colorId,'size_id'=>$sizeId])->first();
		
            if(!empty($billProduct)){
            	if($type == 'plus')$billProduct->update(['quantity' => $billProduct->quantity + $quantity]);
				else{
					if($billProduct->quantity > 0)
					$billProduct->update(['quantity' => $billProduct->quantity - $quantity]);

				} 
				return $billProduct->quantity;
            	

            }else{
            	if($mallId != 0)
            	BillProduct::create(['bill_id'=>$billUser->id,'product_id'=>$productId,'color_id'=>$colorId,'size_id'=>$sizeId,'mall_id'=>$mallId,'product_coast'=>$price,'quantity'=>$quantity]);
            }
           

	}

	function getAllQuantity($billUser){
		$allQuantity = BillProduct::where(['bill_id'=>$billUser->id])->pluck('quantity')->toArray();
		 $sumQuantity = array_sum($allQuantity);
		 return $sumQuantity;
		
	}

	function deleteProductBill($productId,$colorId,$sizeId){
		if(!session('login'))return [false];
		$userId = session('login');
		$billUser = Bill::whereUser_id($userId)->where('status','opened')->orderBy('created_at', 'desc')->first();
		if(empty($billUser) || $billUser->status!='opened' ){
			return [false];
		}else{
			if($colorId == 0) $colorId = null;
            if($sizeId == 0) $sizeId = null;
			$billProduct = BillProduct::where(['product_id' => $productId,'bill_id'=>$billUser->id,'color_id'=>$colorId,'size_id'=>$sizeId])->first();

            if(!empty($billProduct)){
            	$product_coast = $billProduct->product_coast * $billProduct->quantity;
            	//return [true,$product_coast];
            	$returnedPrice = $billUser->total_coast - $product_coast;
            	$billUser->update(['total_coast'=>$returnedPrice]);
            	$billProduct->delete();
            	$sumQuantity = $this->getAllQuantity($billUser);
            	return [true,$billUser->total_coast,$sumQuantity];
            }else{

            	return [false];
            }
            
		}

	}
	// , function ($query) {$query->with('users');}

	function getBestSellerProducts($all = false,$skip = 0){
		if(!$all){
			$BestSellerProducts  = BillProduct::select('product_id')
		    ->groupBy('product_id')
		    ->orderByRaw('COUNT(*) DESC')
		    ->inRandomOrder()
		    ->limit(6)->with(['product'=>function($query){
			$query->where('stock','>=',1);
			}])->take(10)
		    ->get();
		    return $BestSellerProducts;
		}else{
			$BestSellerProductsByDep = [];
			$subDepartments = Department::whereNotNull('parent')->get();
            foreach ($subDepartments as $department) {
            	$departmentId = $department->id;
            	$BestSellerProducts  = BillProduct::select('product_id')
				->whereHas('product',function($query) use($departmentId){
					return $query->where('department_id', '=', $departmentId);
				})
			    ->groupBy('product_id')
			    ->orderByRaw('COUNT(*) DESC')
			    ->limit(6)->with(['product'=>function($query){
				$query->where('stock','>=',1);
				}])->get();
			    $BestSellerProductsByDep[$department->name_en] = $BestSellerProducts;
                
            }
            return $BestSellerProductsByDep;
			
		}
		
		
	}

	function getBestSellerProductsDefinedDep($skip,$departmentId){
            	$BestSellerProductsDefinedDep  = BillProduct::select('product_id')
				->whereHas('product',function($query) use($departmentId){
					return $query->where('department_id', '=', $departmentId);
				})
			    ->groupBy('product_id')
			    ->orderByRaw('COUNT(*) DESC')
			    ->limit(6)->with(['product'=>function($query){
				$query->where('stock','>=',1);
				}])->skip($skip)
			    ->take(10)
			    ->get();
			    //$BestSellerProductsDefinedDep[$department->name_en] = $BestSellerProducts;

			    return $BestSellerProductsDefinedDep;

	}

	function checkDepartment($departmentName){
		$department = Department::where('name_en',$departmentName)->orWhere('name_ar',$departmentName)->first();
		if(!empty($department))return $department;
		else return false;
	}

	function getMalls($all = false,$skip = 0){
		if(!$all){
			$malls = Mall::inRandomOrder()->take(10)->get();
		}else{
			$malls = Mall::skip($skip)->take(10)->get();
		}
		return $malls;
		
	}
	function getProductsWithSale($all = false,$skip = 0){
		date_default_timezone_set("Asia/Muscat");
		$currentDate = Carbon::now();
		$tomorrow = Carbon::tomorrow();
		if(!$all){
			for($i=0;$i<10;$i++){
				$products = Product::where('offer_end_at','=',$tomorrow->format('Y-m-d'))->where('stock','>=',1)->with('users')->inRandomOrder()->take(6)->get();
				if(count($products))return [$currentDate->format('Y-m-d H:i:s'),$tomorrow->format('Y-m-d H:i:s'),$products];
				else $tomorrow = $tomorrow->addDays(1);
			}
			return [$currentDate->format('Y-m-d H:i:s'),$tomorrow->format('Y-m-d H:i:s'),$products];
			
		}else{
			date_default_timezone_set("Asia/Muscat");
			$currentDate = Carbon::now();
			$products = Product::where('offer_end_at','>=',$currentDate->format('Y-m-d'))->where('stock','>=',1)->with('users')->skip($skip)->take(6)->get();
			return $products;
		}
		//return $products;

	}

	function checkProductWitoutStock($productId){
		$product = Product::find($productId);

		if(!empty($product)){
			return $product;
		}
		return false;

	}

	function checkProduct($productId){
		$product = Product::find($productId)->where('stock','>=',1);

		if(!empty($product)){
			return $product;
		}
		return false;

	}

	function getDate(){
		date_default_timezone_set("Asia/Muscat");
		$tomorrow =  Carbon::tomorrow();
		// $afterTomorrow = $tomorrow->addDays(2);
		// $diff = $tomorrow->diffInDays($afterTomorrow);
		return $tomorrow->format('Y-m-d H:i:s');
	}

	function userLoveProduct($productId,$userId){
		//ProductUser::insertOrIgnore(['product_id'=>$productId , 'user_id'=>$userId]);
		$productUser = ProductUser::where('user_id',$userId)->where('product_id',$productId)->first();
		if(empty($productUser)){
			ProductUser::insert(['product_id'=>$productId , 'user_id'=>$userId]);
			return 1;
		}else{
			$productUser->delete();
			return 0;
		}
	}

	function addProductToCardDefault($productId){
		$mallProduct = MallProduct::where('product_id',$productId)->with('mall')->first();
		$sizeProduct = SizeProduct::where('product_id',$productId)->inRandomOrder()->with('size')->first();
		$colorProduct = ColorProduct::where('product_id',$productId)->inRandomOrder()->with('color')->first();
		// if(empty($sizeProduct)){
		// 	$product = SizeProduct::where('porduct_id',$porductId)->first();
		// }
		
		if(empty($mallProduct))return false;
		if(empty($sizeProduct))$sizeId = null;
		else $sizeId = $sizeProduct->size->id;
		if(empty($colorProduct))$colorId = null;
		else $colorId = $colorProduct->color->id;

		$sumQuantityAndTotalCost = $this->sumPrice($productId,$colorId,$sizeId,$mallProduct->mall->id,1);
        // if($sumQuantityAndTotalCost == 'login')return 'login';
        // if($sumQuantityAndTotalCost == false)return false;

		return $sumQuantityAndTotalCost;
	}

	function evaluationProduct($productId,$userId,$star){
		$evaluationProduct = ProductEvaluation::where('user_id',$userId)->where('product_id',$productId)->first();
		
		if(empty($evaluationProduct)){
			ProductEvaluation::insert(['product_id'=>$productId , 'user_id'=>$userId ,'evaluation'=>$star]);
			$return = 1;
		}else{
			$evaluationProduct->update(['evaluation'=>$star]);
			$return = 0;
		}

		$selects = array(
			    'SUM(evaluation) AS sum',
			    'COUNT(*) AS count'
			);
		$evaluation = ProductEvaluation::where('product_id',$productId)->selectRaw(implode(',', $selects))->get();
		$newEvaluation = $evaluation[0]->sum/$evaluation[0]->count;
		Product::find($productId)->update(['evaluation'=>$newEvaluation]);
		return $return;
	}

	function search($searchSelect,$searchQuery){
		$arr = ['malls','products','departments'];
		$searchResult = [];
		if($searchQuery == '')return [$searchResult];
		if(in_array($searchSelect, $arr)){
			$query = 'select * from '.$searchSelect.' where name_en like \'%'.$searchQuery.'%\' or name_ar like \'%'.$searchQuery.'%\'';
			$searchResult[$searchSelect] = DB::select($query);
			return $searchResult;
			
		}else{
			$searchResult['products'] = Product::where('name_en','like','%'.$searchQuery.'%')->orWhere('name_ar','like','%'.$searchQuery.'%')->get();
			//$searchResult['departments'] = Department::whereNotNull('parent')->where('name_en','like','%'.$searchQuery.'%')->orWhere('name_ar','like','%'.$searchQuery.'%')->get();
			$searchResult['departments'] = Department::where(function ($query) use($searchQuery){
			    $query->where('name_en','like','%'.$searchQuery.'%')
			          ->orWhere('name_ar','like','%'.$searchQuery.'%');
			})->whereNotNull('parent')->get();
			$searchResult['malls'] = Mall::where('name_en','like','%'.$searchQuery.'%')->orWhere('name_ar','like','%'.$searchQuery.'%')->get();
			return $searchResult;

		}

	}

	function checkIfEmpty($searchResult){
		$empty = 0;
		foreach ($searchResult as $key => $element) {
			if(!count($element))$empty=$empty+1;
		}
		if(count($searchResult)==3 && $empty==3)return true;
		if(count($searchResult)==1 && $empty==1)return true;
		return false;
		
	}

	function getLastBills($userId){
		$billsIds = Bill::whereUser_id($userId)->orderBy('created_at', 'desc')->take(4)->pluck('id')->toArray();
		$billsIds = array_unique($billsIds);
		
		return $billsIds;
	}


	function getDepsIdsByBill($billsIds){

		$depsIds = Product::whereHas('bills',function($query) use($billsIds) {
			return $query->whereIn('bill_id',$billsIds);
		})->pluck('department_id')->toArray();

		$depsIds = array_unique($depsIds);
		return $depsIds;
	}

	function getProductsByBill($userId){
		$billsIds = $this->getLastBills($userId);
		$depsIds = $this->getDepsIdsByBill($billsIds);
		$products = Product::whereIn('department_id',$depsIds)->where('stock','>=',1)->inRandomOrder()->with('malls')->with('users')->take(6)->get();
		return $products;
	}

	function getProductsByMallFollowing(){
		$mallsIds = MallUser::where('user_id',session('login'))->pluck('mall_id')->toArray();

		$productsFollow = Product::where('stock','>=',1)->whereHas('malls',function($query) use($mallsIds){
					return $query->whereIn('mall_id',$mallsIds);
				})->inRandomOrder()->with('malls')->with('users')->take(6)->get();
		return $productsFollow;
	}


	function justForYouProduct($all = false,$skip = 0){
		// $products = Product::whereHas('users',function($query){
		// 		return $query->where('user_id','=',5);
		// 	})->inRandomOrder()->with('malls')->take(6)->get();
		// return $products;
		if(!$all){
			if(session('login')){
				$products = Product::where('stock','>=',1)->whereHas('users',function($query){
					return $query->where('user_id','=',session('login'));
				})->inRandomOrder()->with('malls')->with('users')->take(6)->get();

				$productsFollow = $this->getProductsByMallFollowing();
				$getProductsByBill = $this->getProductsByBill(session('login'));


				$productsGet = $this->getProducts();

				$products = $productsGet->merge($products);
				$products = $products->merge($productsFollow);
				$products = $products->merge($getProductsByBill);

				//$productUser = ProductUser::where('id',session('login'))->inRandomOrder()->with('malls')->take(6)->get();
			}else{
				$products = $this->getProducts();
				
			}

		}else{

			if(session('login')){
			$products = Product::where('stock','>=',1)->whereHas('users',function($query){
				return $query->where('user_id','=',session('login'));
			})->inRandomOrder()->with('malls')->with('users')->skip($skip)->take(6)->get();

			$productsFollow = $this->getProductsByMallFollowing();
			$getProductsByBill = $this->getProductsByBill(session('login'));

			$productsGet = $this->getProducts($all , $skip);

			$products = $productsGet->merge($products);
			$products = $products->merge($productsFollow);
			$products = $products->merge($getProductsByBill);

			//$productUser = ProductUser::where('id',session('login'))->inRandomOrder()->with('malls')->take(6)->get();
			}else{
				$products = $this->getProducts($all , $skip);
			}

		}
		return $products->take(6);
		
	}
}

?>
