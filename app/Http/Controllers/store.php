<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\classes\storeClass;
use App\Http\Controllers\classes\indexClass;
use App\Mall;

class store extends Controller
{
    function showStore($mallId,$departmentId,storeClass $storeClass,indexClass $indexClass){
        
        if(!session('login') && !\Cookie::get('remembered'))session(['back'=>\Request::url()]);
        if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
    	$mall = $storeClass->getMallInfo($mallId);

    	if($mall == false)return \Redirect::route('home.get');
        if($departmentId != 'all'){
            $department = $storeClass->checkDepartment($departmentId);
            if(!$department) return \Redirect::route('home.get');
        }
        // $departmentsParents = $indexClass->getDepartmentsWithParent2();
        // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();
        //$sumQuantityAndTotalCost = $indexClass->checkLogin();

        /*
        *
        *   this elements is necassery for all pages in web site
        */
        $arr = $indexClass->getPrimaryElementForAllPages('store');

        $ads = $storeClass->getAds($mallId);
        $productsByDep = $storeClass->getProductsByDep($mallId,$departmentId);
        $departments = $storeClass->getDepartments($mallId);
        
        $countFollowers = $mall->followers;

        //dd($productsByDep);

    	$arr1 = [
    		'mall'=>$mall,
            'productsByDep' =>$productsByDep,
            // 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
            'ads' => $ads,
            'departments' => $departments,
            // 'departmentsParents' => $departmentsParents,
            // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
            // 'departmentsParents' => $departmentsParents[0],
            // 'mainDep' => $departmentsParents[1], 
            //'active' => 'store', 
            'countFollowers' => $countFollowers,  

    	];

    	return view('user_layouts.store2',array_merge_recursive($arr,$arr1));
    }

    function postStore($mallId,indexClass $indexClass , storeClass $storeClass,Request $request){
        $arr = [
            'productsWithSale' => [],
            'productsJustForYou' => [], 
            'productsBestSellingByDep' => [],
            'malls' => [],
            'productsByDep' => [],

            ];
        if(Request()->ajax()){
            $mall = $storeClass->getMallInfo($mallId);
            if($mall == false)return response()->json(['operation' => 'fail']);

            if(Request()->input('button') == 'showMore'){
                $departmentName = $request->input('departmentName');
                $skip = $request->input('skip');
                $department = $indexClass->checkDepartment($departmentName);
                if($department == false) return response()->json(['operation' => 'fail' ,'message'=>'invalid info']);

                $productsByDep = $storeClass->getProductsByDefinedDep($mallId,$department->id,$skip);
                if(!count($productsByDep)){
                    return response()->json(['operation' => 'fail','message'=>'no more items']);
                }
                $skip = $skip + count($productsByDep);
                $arr['productsByDep'] = $productsByDep;
                $view = View('user_layouts.loadMoreAjax',$arr)->render();
                return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);

            }elseif(Request()->input('button') == 'follow'){
                
                if(!session('login') && !\Cookie::get('remembered')){
                    return response()->json(['operation' => 'login']);
                }
                if(!session('login'))session(['login'=> \Cookie::get('remembered')]);

                $followOrUnfollow = $storeClass->userFollowMall($mallId,session('login'));
                return response()->json(['operation' => 'success','follow'=>$followOrUnfollow]);
            }elseif(Request()->input('button') == 'searchByMall'){
                $searchQuery = Request()->input('searchQuery');

                $searchResult = $storeClass->searchByMall($searchQuery,$mallId);
                $empty = $indexClass->checkIfEmpty($searchResult);
                //if(!$searchResult)return response()->json(['operation' => 'failed']);
                $view = View('user_layouts/searchResultAjax',['searchResult'=>$searchResult,'empty'=>$empty])->render();
                return response()->json(['operation' => $searchResult,'view'=>$view]);

            }

        }
    }

    function showStoreBrand($mallId,$departmentId,storeClass $storeClass,indexClass $indexClass){
        if(!session('login') && !\Cookie::get('remembered'))session(['back'=>\Request::url()]);
        if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
        //dd($storeClass->filterProducts(1,'price-desc',[-1],[-1],[-1],0,200,3));
        //dd($storeClass->getProductsSortedAndByDep(1,'price-asc',3));
        //dd($storeClass->filterUniform(1,'',[],[1,2],[1],0,200,'offer_end_at','>=','$currentDate','created_at','desc',3));
        $mall = $storeClass->getMallInfo($mallId);
        if($mall == false)return \Redirect::route('home.get');
        if($departmentId != 'all'){
            $department = $storeClass->checkDepartment($departmentId);
            if(!$department) return \Redirect::route('home.get');
        }
        // $departmentsParents = $indexClass->getDepartmentsWithParent2();
        // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();

        /*
        *
        *   this elements is necassery for all pages in web site
        */
        $arr = $indexClass->getPrimaryElementForAllPages('store');

        $ads = $storeClass->getAds($mallId);
        $productsByDep = $storeClass->getProductsByDep($mallId,$departmentId);
        $departments = $storeClass->getDepartments($mallId);
        $sizes = $storeClass->getSizes($mallId,$departments);
        $colors = $storeClass->getColors($mallId);
        //$sumQuantityAndTotalCost = $indexClass->checkLogin();
        $countFollowers = $mall->followers;

        //dd($productsByDep);

        $arr1 = [
            'mall'=>$mall,
            'productsByDep' =>$productsByDep,
            // 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
            'ads' => $ads,
            'departments' => $departments,
            'categoryId' => $departmentId,
            // 'departmentsParents' => $departmentsParents,
            // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
            // 'departmentsParents' => $departmentsParents[0],
            // 'mainDep' => $departmentsParents[1], 
            //'active' => 'store', 
            'countFollowers' => $countFollowers,
            'sizes' => $sizes,
            'colors' => $colors,  

        ];

        return view('user_layouts.storebrand',array_merge_recursive($arr,$arr1));

    }  

    function postStoreBrand($mallId , Request $request ,storeClass $storeClass,indexClass $indexClass){
        if(Request()->ajax()){
            $sortBy = Request()->input('sortBy');
            $departmentId = Request()->input('categoryId');
            $stars = Request()->input('stars');
            $colors = Request()->input('colors');
            $sizes = Request()->input('sizes');
            $fromPrice = Request()->input('fromPrice');
            $toPrice = Request()->input('toPrice');

            if(is_null($stars)) $stars = [-1];
            if(is_null($colors)) $colors = [-1];
            if(is_null($sizes)) $sizes = [-1];
            if(is_null($fromPrice) || $fromPrice <= 0)$fromPrice = 0;
            if(is_null($toPrice) || $toPrice <= 0)$toPrice = 100000;

            $mall = $storeClass->getMallInfo($mallId);
            if($mall == false)return response()->json(['operation' => 'fail']);

            if($departmentId != 'all'){
                    $department = $storeClass->checkDepartment($departmentId);
                    if(!$department) return response()->json(['operation' => 'fail']);
                }

            if(Request()->input('button') == 'sortBy' || Request()->input('button') == 'checkbox'){

                $productsByDep = $storeClass->filterProducts($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$departmentId);

                if(!$productsByDep) return response()->json(['operation' => 'fail']);
                $arr['productsByDep'] = $productsByDep;
                $view = View('user_layouts.sortByResultAjax',$arr)->render();
                return response()->json(['operation' => 'success','view'=>$view]);

            }elseif(Request()->input('button') == 'showMore'){
                $arr = [
                    'productsWithSale' => [],
                    'productsJustForYou' => [], 
                    'productsBestSellingByDep' => [],
                    'malls' => [],
                    'productsByDep' => [],
                    'productsByDepFilter' => [],

                    ];

                $departmentName = $request->input('departmentName');
                $skip = $request->input('skip');
                $department = $indexClass->checkDepartment($departmentName);
                if($department == false) return response()->json(['operation' => 'fail' ,'message'=>'invalid info']);

                $productsByDepFilter = $storeClass->filterProducts($mallId,$sortBy,$stars,$colors,$sizes,$fromPrice,$toPrice,$department->id,$skip);
                if(!count($productsByDepFilter)){
                    return response()->json(['operation' => 'fail','message'=>'no more items']);
                }
                $skip = $skip + count($productsByDepFilter);
                $arr['productsByDepFilter'] = $productsByDepFilter;
                $view = View('user_layouts.loadMoreAjax',$arr)->render();
                return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);

            }
        }
    }  

    function showStoresByDep(Request $request ,$departmentId,indexClass $indexClass , storeClass $storeClass){
        if(!session('login') && !\Cookie::get('remembered'))session(['back'=>\Request::url()]);
        if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
        /*
        *
        * this arrays are necassery for subPages(productsWithSale,bestSellingProducts,...)
        */
        $arr = $indexClass->makeArraysSubIndex();

        /*
        *
        *   this elements is necassery for all pages in web site
        */

        $arr1 = $indexClass->getPrimaryElementForAllPages('categories');

        $arr['productsJustForYou'] = $indexClass->justForYouProduct();

        $page = 'malls';
        $department = $storeClass->checkDepartment($departmentId);
        if(!$department)return back();
        $arr['malls'] = $indexClass->getStoresByDefinedDep($departmentId,0);

        $ads = $indexClass->getAds();
        $arr2 = [
        'ads' => $ads,
        'active' => 'categories',
        ];

        return view('user_layouts.subPages.'.$page,array_merge_recursive($arr,$arr1,$arr2));
    }

    function postStoresByDep(Request $request ,$departmentId,indexClass $indexClass , storeClass $storeClass){
        if(Request()->ajax()){

            $skip = $request->input('skip');
            $arr = $indexClass->makeArraysSubIndex();

            $department = $storeClass->checkDepartment($departmentId);
            if(!$department)return response()->json(['operation' => 'fail' ,'message'=>'some thing happen']);

            $malls = $indexClass->getStoresByDefinedDep($departmentId,$skip);
            if(!count($malls)){
                return response()->json(['operation' => 'fail' ,'message'=>'no more items']);
            }
            $skip = $skip + count($malls);
            $arr['malls'] = $malls;
            $view = View('user_layouts.loadMoreAjax',$arr)->render();
            return response()->json(['operation' => 'success','skip'=>$skip,'view'=>$view]);
        }
    }


}
