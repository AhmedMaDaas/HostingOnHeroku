<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;
class subIndex extends Controller
{
    function showProducts($productsType,indexClass $indexClass){
        $productsWithSale = [];
        $productsJustForYou = [];
        $productsBestSellingByDep = [];
        $malls = [];

        $departmentsParents = $indexClass->getDepartmentsWithParent();
        $sumQuantityAndTotalCost = $indexClass->checkLogin();

        $productsJustForYou = $indexClass->justForYouProduct();

    	if($productsType == 'products-with-sale'){
    		$productsWithSale = $indexClass->getProductsWithSale(true,0);
    		
    	}elseif($productsType == 'stores'){
            $malls = $indexClass->getMalls();

        }elseif($productsType == 'products-best-selling'){
            $productsBestSellingByDep = $indexClass->getBestSellerProducts(true,0);
            

        }elseif($productsType == 'products-just-for-you'){
            $productsJustForYou = $indexClass->justForYouProduct(true,0);
        }else{

        }
        $ads = $indexClass->getAds();
    	$arr = [
        'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
        'departmentsParents' => $departmentsParents[0],
        'mainDep' => $departmentsParents[1],
        'ads' => $ads,
        'productsWithSale' => $productsWithSale,
        'productsJustForYou' => $productsJustForYou, 
        'productsBestSellingByDep' => $productsBestSellingByDep,
        'malls' => $malls,
        'active' => 'categories',

    	];

    	return view('user_layouts.allProducts',$arr);

    }

    function postShowAll($productsType,indexClass $indexClass,Request $request){
        if(Request()->ajax()){
            $productsWithSale = [];
            $productsJustForYou = [];
            $productsBestSellingByDep = [];
            $malls = [];
            $skip = $request->input('skip');
            $arr = [
            'productsWithSale' => $productsWithSale,
            'productsJustForYou' => $productsJustForYou, 
            'productsBestSellingByDep' => $productsBestSellingByDep,
            'malls' => $malls,

            ];

            if($productsType == 'stores'){

                $malls = $indexClass->getMalls(true,$skip);
                if(!count($malls)){
                    return response()->json(['operation' => 'fail' ,'message'=>'no more items']);
                }
                $skip = $skip + count($malls);
                $arr['malls'] = $malls;
                $view = View('user_layouts.loadMoreAjax',$arr)->render();
                return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);

            }elseif($productsType == 'products-best-selling'){

                $departmentName = $request->input('departmentName');
                $department = $indexClass->checkDepartment($departmentName);
                if($department == false) return response()->json(['operation' => 'fail' ,'message'=>'invalid info']);

                $productsBestSellingByDep = $indexClass->getBestSellerProductsDefinedDep($skip,$department->id);
                if(!count($productsBestSellingByDep)){
                    return response()->json(['operation' => 'fail','message'=>'no more items']);
                }
                $skip = $skip + count($productsBestSellingByDep);
                $arr['productsBestSellingByDep'] = $productsBestSellingByDep;
                $view = View('user_layouts.loadMoreAjax',$arr)->render();
                return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);

            }elseif($productsType == 'products-with-sale'){

                $productsWithSale = $indexClass->getProductsWithSale(true,$skip);
                if(!count($productsWithSale)){
                    return response()->json(['operation' => 'fail' ,'message'=>'no more items']);
                }
                $skip = $skip + count($productsWithSale);
                $arr['productsWithSale'] = $productsWithSale;
                $view = View('user_layouts.loadMoreAjax',$arr)->render();
                return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);

            }elseif($productsType == 'products-just-for-you'){

                $productsJustForYou = $indexClass->justForYouProduct(true,$skip);
                if(!count($productsJustForYou)){
                    return response()->json(['operation' => 'fail' ,'message'=>'no more items']);
                }
                $skip = $skip + count($productsJustForYou);
                $arr['productsJustForYou'] = $productsJustForYou;
                $view = View('user_layouts.loadMoreAjax',$arr)->render();
                return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);
            }else{
                return response()->json(['operation' => 'fail' ,'message'=>'invalid url']);
            }
        }

    }
}
