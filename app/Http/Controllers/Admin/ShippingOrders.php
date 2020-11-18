<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bill;
use App\BillProduct;
use App\Http\Controllers\ShippingStatistics;
use App\Http\Controllers\Notifications;


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

    	$bills = Bill::where('status', '!=', 'opened')->orderBy('id', 'desc')->paginate($this->paginateNumber);

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
    	$bills = Bill::orderBy('id', 'desc')->where('status', 'pending')->paginate($this->paginateNumber);

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

    public function deleteOrder(){
    	$this->validate(request(), [
    		'id' => 'required'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->find(request('id'));
    	if(isset($bill)){
    		$bill->delete();
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'deleted']);
    	}
    	return back();
    }

    public function acceptOrder(){
    	$this->validate(request(), [
    		'id' => 'required'
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
    		'id' => 'required'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->find(request('id'));
    	if(isset($bill)){
    		$bill->update(['status' => 'cancelled']);
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'cancelled']);
    	}
    	return back();
    }

    public function returnOrder(){
    	$this->validate(request(), [
    		'id' => 'required'
    	]);

    	$bill = Bill::where('status', '!=', 'opened')->find(request('id'));
    	if(isset($bill)){
    		$bill->update(['status' => 'pending']);
    	}

    	if(request()->ajax()){
    		return response()->json(['data' => 'returned']);
    	}
    	return back();
    }
}
