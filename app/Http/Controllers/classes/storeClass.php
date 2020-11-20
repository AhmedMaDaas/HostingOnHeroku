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
use App\MallDepartment;
use App\MallProduct;
use App\MallUser;
use App\Size;
use \Carbon\Carbon;

class storeClass{
	

	function __construct()
	{
		
	}

	function getMallInfo($mallId){
		// $mall = Mall::where('id',$mallId)->whereHas('products.Product', function ($query) {
  //   	return $query->where('department_id', '=', 1);
		// })->with('products')->first();

		$mall = Mall::where('id',$mallId)->first();
		//$products = $mall->products()->paginate(1);

		//$mall = Mall::where('id',$mallId)->with('products')->first();
		if(!empty($mall)){
			return $mall;
		}
		return false;
	}

	// function getProductsPaginate($mall,$num){
	// 	$products = $mall->products()->paginate($num);
	// 	return $products;
	// }
	function getProductsByDepartment($mall,$departmentId,$num){
		$products = $mall->products()->whereHas('Product', function ($query) use($departmentId) {
	    	return $query->where('department_id', '=', $departmentId)
	    				 ->where('stock','>=',1);
			})->paginate($num);

		return $products;
	}

	function getMallInfoByDepartment($mallId,$departmentId){
		
			$mall = Mall::where('id',$mallId)->whereHas('products.Product', function ($query) use($departmentId) {
	    	return $query->where('department_id', '=', $departmentId);
			})->first();

			if(!empty($mall)){
				return $mall;
			}
			return false;
	}

	function getDepartmentId($departmentName){
		$departmentId = Department::where('name_en',$departmentName)->value('id');
		if(!empty($departmentId)){
			return $departmentId; 
		}
		return 'allDepartment';
		
	}
	
	function checkPaginate($paginateNum){
		$allPaginateNums = [9,18,32];
		if(in_array($paginateNum, $allPaginateNums))return $paginateNum;
		if($paginateNum == 'all')return '';
		return $paginateNum = 9;

	}


	function getAds($mallId){
		date_default_timezone_set("Asia/Muscat");
		$currentDate = Carbon::now();
		
		$ads = Ad::where('end_at','>=',$currentDate)->whereId($mallId)->get();
		return $ads;

	}


	function getDepartments($mallId){
		// $mall = Mall::where('id',$mallId)->first();
		// $departments = $mall->departments()->->get();

		// $MallDepartments = MallDepartment::where('mall_id',$mallId)->whereHas('department',function($query){
		// 			return $query->whereNotNull('parent');
		// 	})->with('department')->get();
		$deps = [];
		$departments = Department::whereHas('malls.mall',function($query) use($mallId){
					return $query->where('id',$mallId);
			})->get();

		foreach ($departments as $key => $department) {
			$product = Product::whereHas('malls.mall',function($query) use($mallId){
					return $query->where('id',$mallId);
			})->where('department_id',$department->id)->first();

			if(!empty($product)) $deps[] = $department;
		}

		return $deps;
	}


	function getAdsAllStores(){
		$currentDate = Carbon::now();
		
		$ads = Ad::where('end_at','>=',$currentDate)->inRandomOrder()->get();
		return $ads;
	}

	function getAllProducts($departmentId,$num){
		if($departmentId != 'allDepartment'){
			$products = Product::where('stock','>=',1)->where('department_id',$departmentId)->with('malls')->paginate($num);
		}else{
			$products = Product::where('stock','>=',1)->with('malls')->paginate($num);
		}
		
		return $products;
	}

	function getAllDepartments(){
		$departments = Department::with('malls')->get();
		return $departments;
	}

	function getProductsByDep($mallId,$departmentId = 'all'){
		$productsByDep = [];

		if($departmentId != 'all'){
			//$products = Product::where('stock','>=',1)->where('department_id',$departmentId)->with('malls')->paginate($num);
			
			$department = Department::find($departmentId);
        	$products  = MallProduct::where('mall_id',$mallId)
			->whereHas('product',function($query) use($departmentId){
				return $query->where('department_id', '=', $departmentId);
			})
			->with(['product'=>function($query){
			$query->where('stock','>=',1);
			}])
			->take(10)
		    ->get();
		    $productsByDep[$department->name_en] = $products;
		}else{


			$subDepartments = $this->getDepartments($mallId);

            foreach ($subDepartments as $department) {
            	$departmentId = $department->id;
            	$products  = MallProduct::where('mall_id',$mallId)
				->whereHas('product',function($query) use($departmentId){
					return $query->where('department_id', '=', $departmentId);
				})
				->orderBy('created_at', 'desc')
				->with(['product'=>function($query){
				$query->where('stock','>=',1);
				}])
				->take(10)
			    ->get();
			    $productsByDep[$department->name_en] = $products;
                
            }
		}
		
		return $productsByDep;

	}



	function getProductsByDefinedDep($mallId,$departmentId ,$skip = 0){
		$products = Product::where('stock','>=',1)->whereHas('malls.mall',function($query) use($mallId){
					return $query->where('id',$mallId);
			})->where('department_id',$departmentId)->skip($skip)->take(10)->get();

		return $products;

	}

	function checkMall($mallId){
		$mall = Mall::find($mallId);
		if(!empty($mall)) return $mall;
		return false;
	}

	function checkDepartment($departmentId){
		$department = MallDepartment::where('department_id',$departmentId)->first();
		if(!empty($department)) return $department;
		return false;
	}

	function userFollowMall($mallId,$userId){
		//ProductUser::insertOrIgnore(['product_id'=>$productId , 'user_id'=>$userId]);
		$mallUser = MallUser::where('user_id',$userId)->where('mall_id',$mallId)->first();
		if(empty($mallUser)){
			MallUser::insert(['mall_id'=>$mallId , 'user_id'=>$userId]);
			$returnVal = 'UnFollow';
		}else{
			$mallUser->delete();
			$returnVal = 'Folllow';
		}
		$count = $this->getCountFollowers($mallId);
		Mall::find($mallId)->update(['followers'=>$count]);
		return $returnVal;
	}

	function getCountFollowers($mallId){
		$count = MallUser::where('mall_id',$mallId)->count();
		return $count;
	}

	function getSizes($mallId,$departments){
		$depIds = [];
		foreach ($departments as $key => $department) {
			$depIds [] = $department->id;
		}
		$sizes = Size::whereIn('department_id',$depIds)->get();
		return $sizes;
	}

	function getColors($mallId){
	    $owner = \App\Mall::find($mallId)->user_id;
	    return \App\Color::where('owner', $owner)->orWhere('owner', 'admin')->get();
	}

	// function test(){
	// 	$products  = MallProduct::where('mall_id',1)
	// 			->whereHas('product',function($query){
	// 				return $query->where('department_id', '=', 3);
	// 			})
	// 			->join('products', 'mall_products.product_id', '=', 'products.id')
	// 			->orderBy('products.price', 'desc')
	// 			->select('mall_products.*')
	// 			->with('product')
	// 			->take(10)
	// 			->get();

	// 	return $products;
	// }

//->select('mall_products.*')
	// function getProductsSortedAndByDep($mallId,$sortBy,$departmentId = 'all'){
	// 	$productsByDep = [];
	// 	$array = ['noThing','sale','created_at-desc','price-desc' ,'created_at-asc','price-asc'];
	// 	if(!in_array($sortBy, $array))return false;

	// 	if($departmentId != 'all'){
	// 		if($sortBy == 'sale'){
	// 			date_default_timezone_set("Asia/Muscat");
	// 			$currentDate = Carbon::now();

	// 			$department = Department::find($departmentId);
	//         	$products  = MallProduct::where('mall_id',$mallId)
	// 			->whereHas('product',function($query) use($departmentId,$currentDate){
	// 				return $query->where('department_id', '=', $departmentId)
	// 							 ->where('offer_end_at','>=',$currentDate);
	// 			})
	// 			->with('product')
	// 			->take(10)
	// 		    ->get();

	// 		}elseif ($sortBy == 'created_at-desc' || $sortBy = 'created_at-asc') {
	// 			$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

	// 			$department = Department::find($departmentId);
	//         	$products  = MallProduct::where('mall_id',$mallId)
	// 			->whereHas('product',function($query) use($departmentId){
	// 				return $query->where('department_id', '=', $departmentId);
	// 			})
	// 			->with('product')
	// 			->orderBy($split[0],$split[1])
	// 			->take(10)
	// 		    ->get();
				
	// 		}elseif ($sortBy == 'price-desc' || $sortBy = 'price-asc') {
	// 			$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

	// 			$products  = MallProduct::where('mall_id',$mallId)
	// 			->whereHas('product',function($query) use($departmentId){
	// 				return $query->where('department_id', '=', $departmentId);
	// 			})
	// 			->join('products', 'mall_products.product_id', '=', 'products.id')
	// 			->orderBy('products.price', $split[1])
	// 			->select('mall_products.*')
	// 			->with('product')
	// 			->take(10)
	// 			->get();
	// 		}else{
	// 			$products = $this->getProductsByDep($mallId,$departmentId);
	// 		}
			
			
	// 	    $productsByDep[$department->name_en] = $products;
	// 	}else{

	// 		$subDepartments = $this->getDepartments($mallId);

 //            foreach ($subDepartments as $department) {
 //            	$departmentId = $department->id;
 //            	if($sortBy == 'sale'){
	// 			date_default_timezone_set("Asia/Muscat");
	// 			$currentDate = Carbon::now();

	// 			//$department = Department::find($departmentId);
	//         	$products  = MallProduct::where('mall_id',$mallId)
	// 			->whereHas('product',function($query) use($departmentId,$currentDate){
	// 				return $query->where('department_id', '=', $departmentId)
	// 							 ->where('offer_end_at','>=',$currentDate);
	// 			})
	// 			->with('product')
	// 			->take(10)
	// 		    ->get();

	// 			}elseif ($sortBy == 'created_at-desc' || $sortBy = 'created_at-asc') {
	// 				$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

	// 				//$department = Department::find($departmentId);
	// 	        	$products  = MallProduct::where('mall_id',$mallId)
	// 				->whereHas('product',function($query) use($departmentId){
	// 					return $query->where('department_id', '=', $departmentId);
	// 				})
	// 				->with('product')
	// 				->orderBy($split[0],$split[1])
	// 				->take(10)
	// 			    ->get();
					
	// 			}elseif ($sortBy == 'price-desc' || $sortBy = 'price-asc') {
	// 				$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

	// 				$products  = MallProduct::where('mall_id',$mallId)
	// 				->whereHas('product',function($query) use($departmentId){
	// 					return $query->where('department_id', '=', $departmentId);
	// 				})
	// 				->join('products', 'mall_products.product_id', '=', 'products.id')
	// 				->orderBy('products.price', $split[1])
	// 				->select('mall_products.*')
	// 				->with('product')
	// 				->take(10)
	// 				->get();
	// 			}else{
	// 				$products = $this->getProductsByDep($mallId);
	// 			}
	// 			$productsByDep[$department->name_en] = $products;
                
 //            }
	// 	}
		
	// 	return $productsByDep;
	// }

	// function test2($stars,$colors,$sizes){
	// 	date_default_timezone_set("Asia/Muscat");
	// 	$currentDate = Carbon::now();

	// 	$department = Department::find(3);
 //    	$products  = MallProduct::where('mall_id',1)
 //    	->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
 //    	->join('products', 'products.id', '=', 'mall_Products.product_id')
	// 	->whereHas('product',function($query) use($currentDate,$stars,$colors,$sizes){
	// 		return $query->where('department_id', '=', 3)
	// 					 ->where('offer_end_at','>=',$currentDate);
	// 	})
	// 	->where(function ($query) use($colors,$sizes,$stars){
	// 		    $query->whereIn('color_products.color_id',$colors)
	// 		          ->orWhereIn('products.size_id',$sizes)
	// 		          ->orWhereIn('products.size_id',$stars);
	// 	})
	// 	->select('mall_products.*')
	// 	->with('product')
	// 	->distinct()
	// 	->take(10)
	//     ->get();

	//     //$products = $products->unique('id');

	//     return $products;
	// }

	// function filterProduct($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$departmentId='all'){
	// 	$productsByDep = [];
	// 	$array = ['noThing','sale','created_at-desc','price-desc' ,'created_at-asc','price-asc'];
	// 	if(!in_array($sortBy, $array))return false;

	// 	if($departmentId != 'all'){
	// 		$department = Department::find($departmentId);
	// 		if($sortBy == 'sale'){
	// 			date_default_timezone_set("Asia/Muscat");
	// 			$currentDate = Carbon::now();

				
	//         	$products  = MallProduct::where('mall_id',$mallId)
	// 	    	->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
	// 	    	->join('products', 'products.id', '=', 'mall_Products.product_id')
	// 			->whereHas('product',function($query) use($departmentId,$currentDate,$stars,$colors,$sizes){
	// 				return $query->where('department_id', '=', $departmentId)
	// 							 ->where('offer_end_at','>=',$currentDate);
	// 			})
	// 			->where(function ($query) use($colors,$sizes,$stars){
	// 				    $query->whereIn('color_products.color_id',$colors)
	// 				          ->orWhereIn('products.size_id',$sizes)
	// 				          ->orWhereIn('products.size_id',$stars);
	// 			})
	// 			->select('mall_products.*')
	// 			->distinct()
	// 			->with('product')
	// 			->take(10)
	// 		    ->get();

	// 		}elseif ($sortBy == 'created_at-desc' || $sortBy == 'created_at-asc') {
	// 			$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

				
	//         	$products  = MallProduct::where('mall_id',$mallId)
	//         	->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
	// 	    	->join('products', 'products.id', '=', 'mall_Products.product_id')
	// 			->whereHas('product',function($query) use($departmentId){
	// 				return $query->where('department_id', '=', $departmentId);
	// 			})
	// 			->where(function ($query) use($colors,$sizes,$stars){
	// 				    $query->whereIn('color_products.color_id',$colors)
	// 				          ->orWhereIn('products.size_id',$sizes)
	// 				          ->orWhereIn('products.size_id',$stars);
	// 			})
	// 			->select('mall_products.*')
	// 			->distinct()
	// 			->with('product')
	// 			->orderBy($split[0],$split[1])
	// 			->take(10)
	// 		    ->get();
				
	// 		}elseif ($sortBy == 'price-desc' || $sortBy == 'price-asc') {
	// 			$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

	// 			$products  = MallProduct::where('mall_id',$mallId)
	// 			->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
	// 			->join('products', 'mall_products.product_id', '=', 'products.id')
	// 			->whereHas('product',function($query) use($departmentId){
	// 				return $query->where('department_id', '=', $departmentId);
	// 			})
	// 			->orderBy('products.price', $split[1])
	// 			->select('mall_products.*')
	// 			->distinct()
	// 			->with('product')
	// 			->take(10)
	// 			->get();
	// 		}else{
	// 			$products = Product::whereHas('malls.mall',function($query) use($mallId){
	// 				return $query->where('id',$mallId);
	// 			})->where('department_id',$departmentId)
	// 			->where(function ($query) use($colors,$sizes,$stars){
	// 				    $query->whereHas('colors',function($query) use($colors,$sizes,$stars){
	// 						return $query->whereIn('products.color_id',$colors)
	// 						->orWhereIn('products.size_id',$sizes)
	// 				        ->orWhereIn('products.size_id',$stars);
	// 					});
	// 			})
	// 			->take(10)
	// 			->get();
	// 		}
			
			
	// 	    $productsByDep[$department->name_en] = $products;
	// 	}else{

	// 		$subDepartments = $this->getDepartments($mallId);

 //            foreach ($subDepartments as $department) {
 //            	$departmentId = $department->id;
 //            	if($sortBy == 'sale'){
	// 				date_default_timezone_set("Asia/Muscat");
	// 				$currentDate = Carbon::now();

					
	// 	        	$products  = MallProduct::where('mall_id',$mallId)
	// 		    	->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
	// 		    	->join('products', 'products.id', '=', 'mall_Products.product_id')
	// 				->whereHas('product',function($query) use($departmentId,$currentDate,$stars,$colors,$sizes){
	// 					return $query->where('department_id', '=', $departmentId)
	// 								 ->where('offer_end_at','>=',$currentDate);
	// 				})
	// 				->where(function ($query) use($colors,$sizes,$stars){
	// 					    $query->whereIn('color_products.color_id',$colors)
	// 					          ->orWhereIn('products.size_id',$sizes)
	// 					          ->orWhereIn('products.size_id',$stars);
	// 				})
	// 				->select('mall_products.*')
	// 				->distinct()
	// 				->with('product')
	// 				->take(10)
	// 			    ->get();

	// 			}elseif ($sortBy == 'created_at-desc' || $sortBy == 'created_at-asc') {
	// 				$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

					
	// 	        	$products  = MallProduct::where('mall_id',$mallId)
	// 	        	->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
	// 		    	->join('products', 'products.id', '=', 'mall_Products.product_id')
	// 				->whereHas('product',function($query) use($departmentId){
	// 					return $query->where('department_id', '=', $departmentId);
	// 				})
	// 				->where(function ($query) use($colors,$sizes,$stars){
	// 					    $query->whereIn('color_products.color_id',$colors)
	// 					          ->orWhereIn('products.size_id',$sizes)
	// 					          ->orWhereIn('products.size_id',$stars);
	// 				})
	// 				->select('mall_products.*')
	// 				->distinct()
	// 				->with('product')
	// 				->orderBy($split[0],$split[1])
	// 				->take(10)
	// 			    ->get();
					
	// 			}elseif ($sortBy == 'price-desc' || $sortBy == 'price-asc') {
	// 				$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

	// 				$products  = MallProduct::where('mall_id',$mallId)
	// 				->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
	// 				->join('products', 'mall_products.product_id', '=', 'products.id')
	// 				->whereHas('product',function($query) use($departmentId){
	// 					return $query->where('department_id', '=', $departmentId);
	// 				})
	// 				->orderBy('products.price', $split[1])
	// 				->select('mall_products.*')
	// 				->distinct()
	// 				->with('product')
	// 				->take(10)
	// 				->get();
	// 			}else{
	// 				$products = Product::whereHas('malls.mall',function($query) use($mallId){
	// 					return $query->where('id',$mallId);
	// 				})->where('department_id',$departmentId)
	// 				->where(function ($query) use($colors,$sizes,$stars){
	// 					    $query->whereHas('colors',function($query) use($colors,$sizes,$stars){
	// 							return $query->whereIn('color_id',$colors)
	// 							->orWhereIn('products.size_id',$sizes)
	// 					        ->orWhereIn('products.size_id',$stars);
	// 						});
	// 				})
	// 				->take(10)
	// 				->get();
	// 			}
	// 			$productsByDep[$department->name_en] = $products;
                
 //            }
	// 	}
		
	// 	return $productsByDep;
	// }

	function filterProducts($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$departmentId='all',$skip=0){
		$productsByDep = [];
			$array = ['noThing','sale','created_at-desc','price-desc' ,'created_at-asc','price-asc'];
			if(!in_array($sortBy, $array))return false;

			$condetion = $this->getColoumWhere($sortBy);

			if($departmentId != 'all'){
				$department = Department::find($departmentId);
				$products  = $this->filterUniform($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$condetion['coloum'],$condetion['ope'],$condetion['value'],$condetion['orderBy'],$condetion['type'],$departmentId,$skip);
				if($skip > 0)return$products;
				$productsByDep[$department->name_en] = $products;

			}else{

				$subDepartments = $this->getDepartments($mallId);

            	foreach ($subDepartments as $department) {
	            	$departmentId = $department->id;
	            	$products  = $this->filterUniform($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$condetion['coloum'],$condetion['ope'],$condetion['value'],$condetion['orderBy'],$condetion['type'],$departmentId,$skip);
					$productsByDep[$department->name_en] = $products;
            	}

			}


		return $productsByDep;
	}

	function filterUniform($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$coloumWhere,$ope,$valueWhere,$orderBy,$type,$departmentId,$skip=0){
			
			if($stars[0] == -1 && $colors[0] == -1 && $sizes[0] == -1){
				$products  = MallProduct::where('mall_id',$mallId)
					->join('products', 'mall_products.product_id', '=', 'products.id')
					->whereHas('product',function($query) use($departmentId,$coloumWhere,$ope,$valueWhere,$fromPrice,$toPrice){
						return $query->where('department_id', '=', $departmentId)
									 ->where($coloumWhere,$ope,$valueWhere)
									 ->whereBetween('price',[$fromPrice,$toPrice]);
					})
					->orderBy($orderBy, $type)
					->select('mall_products.*')
					->distinct()
					->with('product')
					->skip($skip)
					->take(10)
					->get();

			}else{
				$products  = MallProduct::where('mall_id',$mallId)
					->join('color_products', 'color_products.product_id', '=', 'mall_Products.product_id')
					->join('size_products', 'mall_products.product_id', '=', 'size_products.product_id')
					->join('products', 'mall_products.product_id', '=', 'products.id')
					->whereHas('product',function($query) use($departmentId,$coloumWhere,$ope,$valueWhere,$fromPrice,$toPrice){
						return $query->where('department_id', '=', $departmentId)
									 ->where($coloumWhere,$ope,$valueWhere)
									 ->whereBetween('price',[$fromPrice,$toPrice]);
					})
					->where(function ($query) use($colors,$sizes,$stars){
						    $query->whereIn('color_products.color_id',$colors)
						          ->orWhereIn('size_products.size_id',$sizes)
						          ->orWhereIn('products.evaluation',$stars);
					})
					->orderBy($orderBy, $type)
					->select('mall_products.*')
					->distinct()
					->with('product')
					->skip($skip)
					->take(10)
					->get();

			}
		return $products;

	}

	function getColoumWhere($sortBy){
		$condetion = [];

		date_default_timezone_set("Asia/Muscat");
				$currentDate = Carbon::now();
		if($sortBy == 'sale'){
			
				$condetion['coloum'] = 'offer_end_at';
				$condetion['ope'] = '>=' ;
				$condetion['value'] = $currentDate ;
				$condetion['orderBy'] = 'created_at';
				$condetion['type'] = 'desc';

		}elseif ($sortBy == 'created_at-desc' || $sortBy == 'created_at-asc') {
			$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

			$condetion['coloum'] = 'created_at';
			$condetion['ope'] = '<=' ;
			$condetion['value'] = $currentDate ;
			$condetion['orderBy'] = $split[0];
			$condetion['type'] = $split[1];

		}elseif ($sortBy == 'price-desc' || $sortBy == 'price-asc') {
			$split = explode('-', $sortBy, 2); // Restricts it to only 2 values

			$condetion['coloum'] = 'created_at';
			$condetion['ope'] = '<=' ;
			$condetion['value'] = $currentDate ;
			$condetion['orderBy'] = 'products.price';//''.$split[0];
			$condetion['type'] = $split[1];
		}else{
			$condetion['coloum'] = 'created_at';
			$condetion['ope'] = '<=' ;
			$condetion['value'] = $currentDate ;
			$condetion['orderBy'] = 'created_at';
			$condetion['type'] = 'desc';
		}

		return $condetion;
	}

	function showMoreWithFilter(){

	}

	function searchByMall($searchQuery,$mallId){
		$searchResult = [];
		if($searchQuery == '')return [$searchResult];
		$searchResult['products'] = Product::whereHas('malls',function($query)use($mallId){
			$query->where('mall_id',$mallId);
		})->where('name_en','like','%'.$searchQuery.'%')->orWhere('name_ar','like','%'.$searchQuery.'%')->get();

		return $searchResult;
	}

}

?>
