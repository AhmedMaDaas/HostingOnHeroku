<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;
use App\Bill;
use Mail; 

class index extends Controller
{
    
	
    function home(Request $request){
        
    	$indexClass = new indexClass();
        //dd($indexClass->search('all','s'));
        //dd($indexClass->evaluationProduct(1,5,4));
        //dd($indexClass->getBestSellerProducts());
    	$products = $indexClass->getProducts();
        $departmentsParents = $indexClass->getDepartmentsWithParent();
    	//$departments = $indexClass->getDepartments();
        //$countDepartments = count($departments);
    	$ads = $indexClass->getAds();
        $justForYouProduct = $indexClass->justForYouProduct();
//dd($departmentsParents);
        $sumQuantityAndTotalCost = $indexClass->checkLogin();

        session(['total_coast' => $sumQuantityAndTotalCost['total_coast'],
                 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
        ]);

        $bestSellerProducts = $indexClass->getBestSellerProducts();
        $malls = $indexClass->getMalls();
        $productsWithDates = $indexClass->getProductsWithSale();

    	$arr = [
    	'products'=>$products,
    	//'departments' => $departments,
    	'ads' => $ads,
        //'countDepartments' => $countDepartments,
        'total_coast' => $sumQuantityAndTotalCost['total_coast'],
        'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
        'departmentsParents' => $departmentsParents[0],
        'mainDep' => $departmentsParents[1],
        'bestSellerProducts' => $bestSellerProducts,
        'malls' => $malls,
        'currentDate'  => $productsWithDates[0],
        'endDate' => $productsWithDates[1],
        'productsWithSale' =>$productsWithDates[2],
        'justForYouProduct' => $justForYouProduct,
        'active' => 'home',

    	];
    	return view('user_layouts.homepage',$arr);
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
                if(!session('login')){
                    return response()->json(['operation' => 'login']);
                }

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
                if(!session('login')){
                    return response()->json(['operation' => 'login']);
                }

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
