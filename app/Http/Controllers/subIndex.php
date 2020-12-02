<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;
use App\Http\Controllers\Classes\storeClass;
class subIndex extends Controller
{
    function showProducts($productsType,indexClass $indexClass ,storeClass $storeClass){
        if(!session('login') && !\Cookie::get('remembered'))session(['back'=>\Request::url()]);
        if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
        // $productsWithSale = [];
        // $productsJustForYou = [];
        // $productsBestSellingByDep = [];
        // $malls = [];
        /*
        *
        * this arrays are necassery for subPages(productsWithSale,bestSellingProducts,...)
        */
        $arr = $indexClass->makeArraysSubIndex();

        // $departmentsParents = $indexClass->getDepartmentsWithParent2();
        // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();
        // $sumQuantityAndTotalCost = $indexClass->checkLogin();
        /*
        *
        *   this elements is necassery for all pages in web site
        */

        $arr1 = $indexClass->getPrimaryElementForAllPages('categories');

        $arr['productsJustForYou'] = $indexClass->justForYouProduct();

    	if($productsType == 'products-with-sale'){
            $page = 'productsWithSale';
    		$arr['productsWithSale'] = $indexClass->getProductsWithSale(true,0);
    		
    	}elseif($productsType == 'stores'){
            $page = 'malls';
            $arr['malls'] = $indexClass->getMalls();

        }elseif($productsType == 'products-best-selling'){
            $page = 'productsBestSellingByDep';
            $arr['productsBestSellingByDep'] = $indexClass->getBestSellerProducts(true,0);
            

        }elseif($productsType == 'products-just-for-you'){
            $page = 'productsJustForYou';
            $arr['productsJustForYou'] = $indexClass->justForYouProduct(true,0);
        }else{
            // $page = 'malls';
            // $department = $storeClass->checkDepartment($productsType);
            // if(!$department)return back();
            // $arr['malls'] = $indexClass->getStoresByDefinedDep($productsType,0);
        }
        $ads = $indexClass->getAds();
    	$arr2 = [
        // 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
        // 'departmentsParents' => $departmentsParents,
        // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
        // 'departmentsParents' => $departmentsParents[0],
        // 'mainDep' => $departmentsParents[1],
        'ads' => $ads,
        // 'productsWithSale' => $productsWithSale,
        // 'productsJustForYou' => $productsJustForYou, 
        // 'productsBestSellingByDep' => $productsBestSellingByDep,
        // 'malls' => $malls,
        //'active' => 'categories',

    	];

    	return view('user_layouts.subPages.'.$page,array_merge_recursive($arr,$arr1,$arr2));

    }

    function postShowAll($productsType,indexClass $indexClass,storeClass $storeClass,Request $request){
        if(Request()->ajax()){
            // $productsWithSale = [];
            // $productsJustForYou = [];
            // $productsBestSellingByDep = [];
            // $malls = [];
            $skip = $request->input('skip');
            $arr = $indexClass->makeArraysSubIndex();

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
                // $department = $storeClass->checkDepartment($productsType);
                // if(!$department)return response()->json(['operation' => 'fail' ,'message'=>'some thing happen']);

                // $malls = $indexClass->getStoresByDefinedDep($productsType,$skip);
                // if(!count($malls)){
                //     return response()->json(['operation' => 'fail' ,'message'=>'no more items']);
                // }
                // $skip = $skip + count($malls);
                // $arr['malls'] = $malls;
                // $view = View('user_layouts.loadMoreAjax',$arr)->render();
                // return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);
            }
        }

    }

    // function showStores(Request $request ,$departmentId,indexClass $indexClass , storeClass $storeClass){
    //     if(!session('login') && !\Cookie::get('remembered'))session(['back'=>\Request::url()]);
    //     if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
    //     /*
    //     *
    //     * this arrays are necassery for subPages(productsWithSale,bestSellingProducts,...)
    //     */
    //     $arr = $indexClass->makeArraysSubIndex();

    //     /*
    //     *
    //     *   this elements is necassery for all pages in web site
    //     */

    //     $arr1 = $indexClass->getPrimaryElementForAllPages('categories');

    //     $arr['productsJustForYou'] = $indexClass->justForYouProduct();

    //     $page = 'malls';
    //     $department = $storeClass->checkDepartment($departmentId);
    //     if(!$department)return back();
    //     $arr['malls'] = $indexClass->getStoresByDefinedDep($departmentId,0);

    //     $ads = $indexClass->getAds();
    //     $arr2 = [
    //     'ads' => $ads,
    //     'active' => 'categories',
    //     ];

    //     return view('user_layouts.subPages.'.$page,array_merge_recursive($arr,$arr1,$arr2));
    // }

    // function postStores(Request $request ,$departmentId,indexClass $indexClass , storeClass $storeClass){
    //     if(Request()->ajax()){

    //         $skip = $request->input('skip');
    //         $arr = $indexClass->makeArraysSubIndex();

    //         $department = $storeClass->checkDepartment($departmentId);
    //         if(!$department)return response()->json(['operation' => 'fail' ,'message'=>'some thing happen']);

    //         $malls = $indexClass->getStoresByDefinedDep($departmentId,$skip);
    //         if(!count($malls)){
    //             return response()->json(['operation' => 'fail' ,'message'=>'no more items']);
    //         }
    //         $skip = $skip + count($malls);
    //         $arr['malls'] = $malls;
    //         $view = View('user_layouts.loadMoreAjax',$arr)->render();
    //         return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);
    //     }
    // }
}
