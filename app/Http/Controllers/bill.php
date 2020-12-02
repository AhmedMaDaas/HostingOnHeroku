<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;
use App\Http\Controllers\Classes\billClass;
use App\Http\Controllers\Classes\productClass;
use App\Jobs\MakeNewOrder;

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

class bill extends Controller
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

    function showCheckPage(indexClass $indexClass , billClass $billClass){
        if(session('login') || \Cookie::get('remembered')){
            if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
            session(['back'=>\Request::url()]);
            //dd($billClass->checkQuantityInBill(38));
            // $departmentsParents = $indexClass->getDepartmentsWithParent2();
            // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();
            // $sumQuantityAndTotalCost = $indexClass->checkLogin();

            /*
            *
            *   this elements is necassery for all pages in web site
            */
            $arr = $indexClass->getPrimaryElementForAllPages('checkout');

            $bill = $billClass->checkBill(session('login'));

            /*update product_coast in bill_products table and update total_coast in bill table
            if the the offer is end and product_coast still equal price_offer in bill and bill_products
            or update it's after the offer is start and the product in bill*/
            if($bill){
                $billClass->updatePriceInBill($bill->id,$bill->total_coast,'price','<');
                $billClass->updatePriceInBill($bill->id,$bill->total_coast,'price_offer','>=');
            }

            $relatedProducts = $indexClass->getProductsByBill(session('login'))->take(3);
            if(!count($relatedProducts))$relatedProducts = $indexClass->getProducts()->take(3);  

            $arr1 = [
                    // 'total_coast' => $sumQuantityAndTotalCost['total_coast'],
                    // 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
                    'bill' => $bill,
                    'relatedProducts' => $relatedProducts,
                    // 'departmentsParents' => $departmentsParents,
                    // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
                    //'active' => 'checkout',

                ];
                return view('user_layouts.checkout',array_merge_recursive($arr,$arr1));
        }else{
            return \Redirect::route('login');
        }
    }

    function postCheckPage(Request $request ,indexClass $indexClass,billClass $billClass , productClass $productClass){

    	if(Request()->ajax()){

            if($request->input('button') == 'delete'){
                $products = $request->input('products');
                if(is_null($products))return response()->json(['operation' => 'failed' ]);
                foreach($products as $product){
                    $infoProduct = explode("/",$product);
                    $deleted = $indexClass->deleteProductBill($infoProduct[0],$infoProduct[1],$infoProduct[2]);
                    //if($deleted[0] == true)return response()->json(['operation' => 'succesed','total_coast' => $deleted[1],'sumQuantity' => $deleted[2] ]);
                    if($deleted[0] == false) return response()->json(['operation' => 'failed' ]);
                    
                }
                return response()->json(['operation' => 'succesed','total_coast' => $deleted[1],'sumQuantity' => $deleted[2] ]);
                
            }else{
                
               return response()->json(['operation' => 'failed' ]);
            }
    		
        }else{

            if(!session('login') && !\Cookie::get('remembered'))return \Redirect::route('login');
                if(!session('login'))session(['login'=> \Cookie::get('remembered')]);

                $data = $this->validate(request(),[
                    'country'=>'required',
                    'city'=>'required',
                    'street'=>'required'
                ]);

                $country = $request->input('country');
                $city = $request->input('city');
                $street = $request->input('street');

                $bill = $billClass->checkBill(session('login'));
                if(!$bill)return back()->with('failed', 'oops! , your bill dosen\'t have any products');


                $bill->update(['address'=>$country.'/'.$city.'/'.$street]);
                $checkQuantity = $billClass->checkQuantityInBill($bill->id);

                if($checkQuantity['status'])return back()->with('failed',$checkQuantity['msg']);

            if(isset($_POST['paybal'])){

                $this->setContext();

                $payer = new Payer();
                $payer->setPaymentMethod("paypal");

                $items_products = [];
                foreach($bill->products as $billProduct){
                    // $checkQ = $productClass->checkQuantity($billProduct->product_id,$billProduct->quantity,$billProduct->size_id,$billProduct->color_id);
                    // if(!$checkQ)return back()->with('failed', 'quantity of product : ('.$billProduct->product->name_en.'|'.$billProduct->product->name_ar.') still just '.$billProduct->product->stock);

                    $item1 = new Item();
                    $item1->setName($billProduct->product->name_en.'|'.$billProduct->product->name_ar)
                        ->setCurrency('USD')
                        ->setQuantity($billProduct->quantity)
                        ->setSku($billProduct->product->id) // Similar to `item_number` in Classic API
                        ->setPrice($billProduct->product_coast);

                    $items_products[] = $item1;
                }

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

            }elseif (isset($_POST['cash'])) {
                dispatch(new MakeNewOrder($bill->id));
                $bill->update(['payment'=>'cash']);
                return back()->with('success', 'success');
            }
    		return back()->with('failed', 'oops! , there is something happen');

            //http://localhost:8000/success/true?paymentId=PAYID-L6XTPFA46N67999DM432805E&token=EC-62L03510WW885764E&PayerID=TXAEMLVYETZH6
    	}
    }

    public function makePayment($status ,Request $request ,billClass $billClass){
        if($status == "true"){

            if(isset($request->paymentId) && $request->paymentId != '' &&
                isset($request->token) && $request->token != '' &&
                isset($request->PayerID) && $request->PayerID != ''){

                    $this->setContext();

                    $total_coast = session('total_coast');
                    $shipping_coast = session('shipping_coast');
                    session()->forget('total_coast','shipping_coast');

                    $paymentId = $request->paymentId;
                    $payment = Payment::get($paymentId, $this->apiContext);

                    $execution = new PaymentExecution();
                    $execution->setPayerId($request->PayerID);


                    $transaction = new Transaction();
                    $amount = new Amount();
                    $details = new Details();

                    $details->setShipping($shipping_coast)//chrging
                        ->setTax(0)
                        ->setSubtotal($total_coast);

                    $amount->setCurrency('USD');
                    $amount->setTotal($total_coast + $shipping_coast);
                    $amount->setDetails($details);
                    $transaction->setAmount($amount);

                    $execution->addTransaction($transaction);
                    
                    try {

                        $result = $payment->execute($execution, $this->apiContext);


                        try {
                            $payment = Payment::get($paymentId, $this->apiContext);
                        } catch (Exception $ex) {

                                exit(1);
                            }
                    } catch (Exception $ex) {

                        exit(1);
                    }
                    //return $payment;
                    if(!session('login') && !\Cookie::get('remembered'))return \Redirect::route('login');
                    if(!session('login'))session(['login'=> \Cookie::get('remembered')]);
                    
                    $bill = $billClass->checkBill(session('login'));
                    dispatch(new MakeNewOrder($bill->id));
                    if($payment->state == 'approved'){
                        if(!$bill)return back()->with('failed', 'oops! , there is something happen');
                        $bill->update(['id_payment'=> $payment->id,'payment'=>$payment->payer->payment_method]);
                        dispatch(new MakeNewOrder($bill->id));
                        session()->forget('bill');
                        return back()->with('success', 'success');
                    }
                    
                    //dd($payment);
            }

        }
        if(session('bill')){
            $bill = session('bill');
            $bill->delete();
            session()->forget('bill');
        }
        return back()->with('failed', 'oops! , there is something happen');
    }
}
