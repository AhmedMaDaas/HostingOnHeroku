<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\classes\productClass;
use App\Http\Controllers\classes\indexClass;
use App\Http\Controllers\classes\billClass;
use App\Commint;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\PaymentExecution;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class product extends Controller
{

    protected $apiContext;

    protected function setContext(){

        $this->apiContext = new ApiContext(
                new OAuthTokenCredential(
                        config('payment.accounts.client_id'),
                        config('payment.accounts.secret_client')
                    )
            );

        $this->apiContext->setConfig(config('payment.setting'));
    }


    function showProductPage($productId ,productClass $productClass ,indexClass $indexClass){

        $mallProduct = $productClass->getMallInfo($productId);
        if(!$mallProduct)return \Redirect::route('home.get');
        $product = $productClass->getProduct($productId,$mallProduct->mall_id);
        if(!$product)return \Redirect::route('home.get');
        $table = $this->setOtherDataTable($product->otherData);
        
        $countFollowers = $mallProduct->mall->followers;

        $relatedProducts = $productClass->getSomeProducts($mallProduct->mall_id,$productId);
        //$commintsInPage = 6;
//dd($indexClass->getDepartments());
        $departmentsParents = $indexClass->getDepartmentsWithParent();

        $sumQuantityAndTotalCost = $indexClass->checkLogin();
    	//$product = $productClass->getProduct($productId,$mallId);
        //$commints = $productClass->getCommints($productId,$commintsInPage);
        //if($product == false)return \Redirect::route('home');
    	//$mall = $productClass->getMallInfo($mallId);
        //if($mall == false)return \Redirect::route('home');
    	//$relatedProducts = $productClass->getSomeProducts($mall);
    	
    	$arr = [
    		'product' => $product,
            'countFollowers' => $countFollowers,
    		 'mallProduct' => $mallProduct,
    		 'relatedProducts' => $relatedProducts,
            'total_coast' => $sumQuantityAndTotalCost['total_coast'],
            'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
            //'commints' => $commints,
            'departmentsParents' => $departmentsParents[0],
            'mainDep' => $departmentsParents[1],
            'active' => 'product',
            'table' => $table,

    	];
    	return view('user_layouts.product',$arr);
    }

    function postProductPage(Request $request,$productId ,billClass $billClass,productClass $productClass, indexClass $indexClass){

        if(!session('login'))return \Redirect::route('login');
        
        $product = $productClass->checkProduct($productId);
        if(!$product)return back()->with('failed', 'some thing wrong happen');

        if(isset($_POST['add_product']) || isset($_POST['buy'])){
            $colorId = $request->input('color');
            $sizeId = $request->input('size');
            $mallId = $request->input('mall');
            $quantity = $request->input('quantity');

            $mallId = $productClass->checkMallId($productId , $mallId);
            $colorId = $billClass->checkColorId($colorId,$productId,1);
            $sizeId = $billClass->checkSizeId($sizeId,$productId,1);

            if($colorId == false || $sizeId == false || $mallId == false )return back()->with('failed', 'oops! choose a color and size');
            
            if(isset($_POST['add_product'])){
                $sumQuantityAndTotalCost = $indexClass->sumPrice($productId,$colorId,$sizeId,$mallId,$quantity);
                if($sumQuantityAndTotalCost == 'login')return \Redirect::route('login');
                if($sumQuantityAndTotalCost == false)return back()->with('failed', 'oops! , there is something happen');

                return back()->with('success', 'your product added successfully');

            }else{
                $product = $product->first();
                
                $productPrice = $productClass->checkPriceProduct($product);
                
                $bill = $billClass->createBill(session('login'),$productPrice,$productId,$colorId,$sizeId,$mallId,$quantity);
                session(['bill'=>$bill]);

                $this->setContext();

                $payer = new Payer();
                $payer->setPaymentMethod("paypal");

                $items_products = [];
                //foreach($bill->products as $billProduct){

                    $item1 = new Item();
                    $item1->setName($product->name)
                        ->setCurrency('USD')
                        ->setQuantity($quantity)
                        ->setSku($product->id) // Similar to `item_number` in Classic API
                        ->setPrice($bill->total_coast/$quantity);

                    $items_products[] = $item1;
                //}

                $itemList = new ItemList();
                $itemList->setItems($items_products);

                $details = new Details();
                $details->setShipping($bill->shipping_coast)//تسعيرة الشحن
                    ->setTax(0)//سعر الضرائب
                    ->setSubtotal($bill->total_coast);

                $amount = new Amount();
                $amount->setCurrency("USD")
                    ->setTotal($bill->total_coast + $bill->shipping_coast)
                    ->setDetails($details);

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setDescription("Bazar Alseeb payment products")
                    ->setInvoiceNumber(uniqid());

                $baseUrl = url('/');
                $redirectUrls = new RedirectUrls();
                $redirectUrls->setReturnUrl("$baseUrl/success/true")
                    ->setCancelUrl("$baseUrl/success/false");

                $payment = new Payment();
                $payment->setIntent("sale")
                    ->setPayer($payer)
                    ->setRedirectUrls($redirectUrls)
                    ->setTransactions(array($transaction));

                $request = clone $payment;

                try {
                    $payment->create($this->apiContext);
                } catch (Exception $ex) {
                    //ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
                    exit(1);
                }

                $approvalUrl = $payment->getApprovalLink();

                session(['total_coast'=>$bill->total_coast,
                         'shipping_coast' => $bill->shipping_coast,
                    ]);

                return Redirect($approvalUrl);

            }
            $bill->delete();
            

        }elseif(isset($_POST['add_commint'])){
            $commint = $request->input('commint');
            if(session('login')){
                Commint::create(['product_id'=>$productId,'user_id'=>session('login'),'commint'=>$commint]);
                return back()->with('success', 'your commint added successfully');
            }
            return \Redirect::route('login');

        }elseif(isset($_POST['add_question'])){
            $question = $request->input('question');
            if(session('login')){
                Commint::create(['product_id'=>$productId,'user_id'=>session('login'),'commint'=>$question]);
                return back()->with('success', 'your question added successfully');
            }
            return \Redirect::route('login');

        }
        
        return back();

    }

    private function addCell($data){
        $cell = [
            'text' => $data->text,
            'rowspan' => $data->rowspan,
            'colspan' => $data->colspan,
        ];
        return $cell;
    }

    private function addColumn($rows, $data){
        foreach ($rows as $key => $row) {
            if($key == $data->row) {
                $rows[$data->row][] = $this->addCell($data);
                return $rows;
            };
        }
        $rows[$data->row] = [$this->addCell($data)];
        return $rows;
    }

    private function setOtherDataTable($otherData){
        $rows = [];
        foreach ($otherData as $key => $data) {
            $rows = $this->addColumn($rows, $data);
        }
        return $rows;
    }
}
