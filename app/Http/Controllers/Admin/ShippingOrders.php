<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bill;
use App\BillProduct;
use App\Http\Controllers\ShippingStatistics;
use App\Http\Controllers\Notifications;
use Batch;
use App\Product;
use App\SizeProduct;
use App\ColorProduct;


class ShippingOrders extends Controller
{
	private $paginateNumber = 5;

	public function home(){
		$statistics = new ShippingStatistics();
        $statistics->mainStatistics();
		$statistics->overview();
		$topCustomersBills = $statistics->topCustomers();
		$topStoresBills =  $statistics->topStores();

        $notifications = new Notifications();

        if(request()->ajax()){
            return response()->json(['completed' => $statistics->getCompletedOrders(), 'pending' => $statistics->getPendingOrders(), 'cancelled' => $statistics->getCancelledOrders()]);
        }

    	return view('admin.home', ['statistics' => $statistics, 'topCustomersBills' => $topCustomersBills, 'topStoresBills' => $topStoresBills, 'notifications' => $notifications]);
    }

    public function allOrders(){
    	$statistics = new ShippingStatistics();
        $statistics->mainStatistics();

    	$bills = Bill::where('status', '!=', 'opened')->with(['products' => function($query){
            return $query->with(['size', 'color', 'product']);
        }])->orderBy('id', 'desc')->paginate($this->paginateNumber);

    	$pagesNumber = ceil($bills->total()/$this->paginateNumber);

        $page = request('page') == null ? 1 : request('page');

        $notifications = new Notifications();

        if(request()->ajax()){
            return view('admin.shippings.plugins.orders_table_overview', ['bills' => $bills,
                                                         'pagesNumber' => $pagesNumber,
                                                         'page' => $page])->render();
        }

    	return view('admin.shippings.pages.shipping_overview', ['bills' => $bills,
    													 'pagesNumber' => $pagesNumber,
    													 'page' => $page,
    													 'statistics' => $statistics,
                                                         'notifications' => $notifications]);
    }

    public function pendingOrders(){
    	$bills = Bill::orderBy('id', 'desc')->with(['products' => function($query){
            return $query->with(['size', 'color', 'product']);
        }])->where('status', 'pending')->paginate($this->paginateNumber);

    	$pagesNumber = ceil($bills->total()/$this->paginateNumber);

        $page = request('page') == null ? 1 : request('page');

        $notifications = new Notifications();

        if(request()->ajax()){
            return view('admin.shippings.pages.shipping_orders', ['bills' => $bills,
                                                                'pagesNumber' => $pagesNumber,
                                                                'page' => $page,
                                                                'notifications' => $notifications])->renderSections()['content'];
        }

    	return view('admin.shippings.pages.shipping_orders', ['bills' => $bills, 'pagesNumber' => $pagesNumber, 'page' => $page, 'notifications' => $notifications]);
    }

    public function updateOrder(){
        $this->validate(request(), [
            'id' => 'required|numeric',
            'shipping_coast' => 'required|numeric',
        ]);

        $bill = Bill::where('status', '!=', 'opened')->find(request('id'));
        if(isset($bill)){
            $bill->update(['shipping_coast' => request('shipping_coast')]);
        }

        if(request()->ajax()){
            return response()->json(['data' => 'updated']);
        }
        return back();
    }

    public function deleteOrder(){
    	$this->validate(request(), [
    		'id' => 'required|numeric'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->find(request('id'));
    	if(isset($bill)){
            if($bill->status != 'cancelled') $this->updateProduct($bill->products, 'cancel');
    		$bill->delete();
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'deleted']);
    	}
    	return back();
    }

    public function acceptOrder(){
    	$this->validate(request(), [
    		'id' => 'required|numeric'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->find(request('id'));
    	if(isset($bill)){
    		$bill->update(['status' => 'completed']);
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'completed']);
    	}
    	return back();
    }

    public function rejectOrder(){
    	$this->validate(request(), [
    		'id' => 'required|numeric'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->with(['products' => function($query){
            return $query->with(['product' => function($query){
                return $query->with(['sizes' => function($query){
                    return $query->with('size');
                }, 'colors' => function($query){
                    return $query->with('color');
                }]);
            }]);
        }])->find(request('id'));

    	if(isset($bill)){
    		$bill->update(['status' => 'cancelled']);
            $this->updateProduct($bill->products, 'cancel');
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'cancelled']);
    	}
    	return back();
    }

    public function returnOrder(){
    	$this->validate(request(), [
    		'id' => 'required|numeric'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->with(['products' => function($query){
            return $query->with(['product' => function($query){
                return $query->with(['sizes' => function($query){
                    return $query->with('size');
                }, 'colors' => function($query){
                    return $query->with('color');
                }]);
            }]);
        }])->find(request('id'));

    	if(isset($bill)){
    		$bill->update(['status' => 'pending', 'new' => 1]);
            $this->updateProduct($bill->products, 'return');
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'returned']);
    	}
    	return back();
    }

    private function updateProduct($productsInBill, $operation){
        $sizesData = [];
        $colorsData = [];
        foreach ($productsInBill as $key => $productInBill) {
            $sizeId = isset($productInBill->size) ? $productInBill->size->id : 0;
            $colorId = isset($productInBill->color) ? $productInBill->color->id : 0;         
            $sizesData = $this->addOrUpdateRecord($productInBill->product->sizes, $sizesData, $productInBill->product_id, $productInBill->quantity, 'size_id', $sizeId, $operation);
            $colorsData = $this->addOrUpdateRecord($productInBill->product->colors, $colorsData, $productInBill->product_id, $productInBill->quantity, 'color_id', $colorId, $operation);
        }
        $this->updateProductsQuantities($productsInBill, $operation);
        $this->updateProductColors($colorsData);
        $this->updateProductSizes($sizesData);
    }

    private function addOrUpdateRecord($data, $relationData, $productId, $productBillQuantity, $relationColId, $relationId, $operation){
        $relationQuantity = $this->getQuantity($data, $productId, $relationColId, $relationId);
        $id = $this->getId($data, $productId, $relationColId, $relationId);
        $relationData = $this->findRecord($relationData, $id, $productId, $relationColId, $relationId, $relationQuantity, $productBillQuantity, $operation);
        return $relationData;
    }

    private function getId($data, $productId, $relationColId, $relationId){
        foreach ($data as $key => $value) {
            if($value->{$relationColId} == $relationId && $value->product_id == $productId){
                return $value->id;
            }
        }
        return 0;
    }

    private function getQuantity($data, $productId, $relationColId, $relationId){
        foreach ($data as $key => $value) {
            if($value->{$relationColId} == $relationId && $value->product_id == $productId){
                return $value->quantity;
            }
        }
        return 0;
    }

    private function findRecord($data, $recordId, $productId, $relationColId, $relationId, $relationQuantity, $quantity, $operation){
        foreach ($data as $key => $value) {
            if($value[$relationColId] == $relationId && $value['product_id'] == $productId){
                if($operation == 'cancel') $data[$key]['quantity'] += $quantity;
                else $data[$key]['quantity'] -= $quantity;
                return $data;
            }
        }
        $data[] = [
            'id' => $recordId,
            'product_id' => $productId,
            $relationColId => $relationId,
            'quantity' => $operation == 'cancel' ? $relationQuantity + $quantity : $relationQuantity - $quantity,
        ];
        return $data;
    }

    private function updateProductSizes($sizesData){
        $index = 'id';
        Batch::update(new SizeProduct, $sizesData, $index);
    }

    private function updateProductColors($colorsData){
        $index = 'id';
        Batch::update(new ColorProduct, $colorsData, $index);
    }

    private function updateProductsQuantities($productsInBill, $operation){
        $data = $this->getProductsQuantities($productsInBill, $operation);
        $index = 'id';
        Batch::update(new Product, $data, $index);
    }

    private function getProductsQuantities($productsInBill, $operation){
        $data = [];
        foreach ($productsInBill as $key => $productsInBill) {
            $data = $this->addQuantity($productsInBill, $data, $operation);
        }
        return $data;
    }

    private function addQuantity($productsInBill, $data, $operation){
        foreach ($data as $key => $value) {
            if($value['id'] == $productsInBill->product_id){
                $data[$key]['stock'] = $operation == 'cancel' ? $value['stock'] + $productsInBill->quantity : $value['stock'] - $productsInBill->quantity;
                return $data;
            }
        }
        $data[] = [
            'id' => $productsInBill->product_id,
            'stock' => $operation == 'cancel' ? $productsInBill->product->stock + $productsInBill->quantity
                        : $productsInBill->product->stock - $productsInBill->quantity
        ];
        return $data;
    }
}
