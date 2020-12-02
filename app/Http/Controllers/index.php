<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;
use App\Bill;
use Mail; 

class index extends Controller
{
    
	
    function home(Request $request){
        if(!session('login') && !\Cookie::get('remembered'))session(['back'=>\Request::url()]);
        if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
    	$indexClass = new indexClass();
        //dd($indexClass->search('all','s'));
        //dd($indexClass->evaluationProduct(1,5,4));
        //dd($indexClass->getBestSellerProducts());
    	$products = $indexClass->getProducts();
        //$sumQuantityAndTotalCost = $indexClass->checkLogin();
        // $departmentsParents = $indexClass->getDepartmentsWithParent2();
        // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();

        /*
        *
        *   this elements is necassery for all pages in web site
        */
        $arr = $indexClass->getPrimaryElementForAllPages('home');

    	$ads = $indexClass->getAds();
        $justForYouProduct = $indexClass->justForYouProduct();
//dd($indexClass->getDepartments());
        

        session(['total_coast' => $arr['total_coast'],
                 'sumQuantity' => $arr['sumQuantity'],
        ]);

        $bestSellerProducts = $indexClass->getBestSellerProducts();
        $malls = $indexClass->getMalls();
        $productsWithDates = $indexClass->getProductsWithSale();

    	$arr1 = [
    	'products'=>$products,
    	'ads' => $ads,
        // 'total_coast' => $sumQuantityAndTotalCost['total_coast'],
        // 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
        // 'departmentsParents' => $departmentsParents,
        // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
        'bestSellerProducts' => $bestSellerProducts,
        'malls' => $malls,
        'currentDate'  => $productsWithDates[0],
        'endDate' => $productsWithDates[1],
        'productsWithSale' =>$productsWithDates[2],
        'justForYouProduct' => $justForYouProduct,
        //'active' => 'home',

    	];
    	return view('user_layouts.homepage',array_merge_recursive($arr,$arr1));
    }

    function test(){
        $indexClass = new indexClass();
        $billUser = Bill::whereUser_id(2)->orderBy('created_at', 'desc')->first();
        $arr = $indexClass->getAllQuantity($billUser);
        dd($arr);

    }

    function postHome(Request $request,indexClass $indexClass){
        if(Request()->ajax()) {
            
            if(Request()->input('button') == 'love'){
                if(!session('login') && !\Cookie::get('remembered')){
                    return response()->json(['operation' => 'login']);
                }
                if(!session('login'))session(['login'=>\Cookie::get('remembered')]);

                $productId = Request()->input('productId');
                $product = $indexClass->checkProductWitoutStock($productId);
                if(!$product)return response()->json(['operation' => 'failed']);
                //$userId = session('login');
                $statusLove = $indexClass->userLoveProduct($productId,session('login'));
                return response()->json(['operation' => 'succesed','statusLove'=>$statusLove]);

            }elseif(Request()->input('button') == 'search'){
                $searchSelect = Request()->input('searchSelect');
                $searchQuery = Request()->input('searchQuery');
                $searchResult = $indexClass->search($searchSelect,$searchQuery);
                $empty = $indexClass->checkIfEmpty($searchResult);
                //if(!$searchResult)return response()->json(['operation' => 'failed']);
                $view = View('user_layouts/searchResultAjax',['searchResult'=>$searchResult,'empty'=>$empty])->render();
                return response()->json(['operation' => $searchResult,'view'=>$view]);

            }elseif(Request()->input('button') == 'evaluation'){
                if(!session('login') && !\Cookie::get('remembered')){
                    return response()->json(['operation' => 'login']);
                }
                if(!session('login'))session(['login'=>\Cookie::get('remembered')]);

                $productId = Request()->input('productId');
                $star = Request()->input('star');
                if(is_null($star) || $star<0)$star=1;
                $product = $indexClass->checkProduct($productId);
                if(!$product)return response()->json(['operation' => 'failed']);

                $statusEvaluation = $indexClass->evaluationProduct($productId,session('login'),$star);
                return response()->json(['operation' => 'success','statusEvaluation'=>$statusEvaluation]);

            }elseif(Request()->input('button') == 'buyProduct'){
                $productId = Request()->input('productId');

                $sumQuantityAndTotalCost = $indexClass->addProductToCardDefault($productId);
                if($sumQuantityAndTotalCost == 'login')return response()->json(['operation' => 'login']);
                if($sumQuantityAndTotalCost == false)return response()->json(['operation' => 'failed']);

                return response()->json(['operation' => 'success','sumQuantityAndTotalCost'=>$sumQuantityAndTotalCost]);

            }else{
                return response()->json(['operation' => 'failed']);
            }                    
            
        }else{
            $this->validate($request, [
            'email' => 'required|email',
            ]);

            Mail::send('user_layouts.join_us',
                 array(
                     'email' => $request->get('email'),
                 ), function($message) use ($request)
                   {
                      $message->from($request->email);
                      $message->to('laraveltestmail.test@gmail.com');
                   });

            return back()->with('success', 'Thank you for contact us');

        }
    }
}
