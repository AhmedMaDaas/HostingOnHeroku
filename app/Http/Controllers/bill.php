<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;
use App\Http\Controllers\Classes\billClass;
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
        if(session('login')){
            //dd($billClass->updatePriceInBill(37));
            $departmentsParents = $indexClass->getDepartmentsWithParent();
            $sumQuantityAndTotalCost = $indexClass->checkLogin();

            $bill = $billClass->checkBill(session('login'));

            $relatedProducts = $indexClass->getProductsByBill(session('login'))->take(3);
            if(!count($relatedProducts))$relatedProducts = $indexClass->getProducts()->take(3);  

            $arr = [
                    'total_coast' => $sumQuantityAndTotalCost['total_coast'],
                    'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
                    'bill' => $bill,
                    'relatedProducts' => $relatedProducts,
                    //'mallsIds' => $mallsIds,
                    'departmentsParents' => $departmentsParents[0],
                    'mainDep' => $departmentsParents[1],
                    'active' => 'checkout',

                ];
                return view('user_layouts.checkout',$arr);
        }else{
            return \Redirect::route('login');
        }
    }

    function postCheckPage(Request $request ,indexClass $indexClass,billClass $billClass){

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

            if(!session('login'))return \Redirect::route('login');

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

            if(isset($_POST['paybal'])){

                $this->setContext();

                $payer = new Payer();
                $payer->setPaymentMethod("paypal");

                $items_products = [];
                foreach($bill->products as $billProduct){

                    $item1 = new Item();
                    $item1->setName($billProduct->product->name)
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
                    if(!session('login'))return \Redirect::route('login');
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
