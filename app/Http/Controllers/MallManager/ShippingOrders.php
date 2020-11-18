<?php

namespace App\Http\Controllers\MallManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bill;
use App\BillProduct;
use App\Http\Controllers\ShippingStatistics;
use App\DataTables\MallsSalesDatatable;
use App\DataTables\MallSalesDatatable;
use App\Http\Controllers\Notifications;

class ShippingOrders extends Controller
{
	private $paginateNumber = 5;

	public function home(){
		$statistics = new ShippingStatistics();
        $statistics->managerStatistics();
        $statistics->overview();
        $notifications = new Notifications();
    	return view('mall_manager.home', ['statistics' => $statistics, 'notifications' => $notifications]);
    }

    public function mallsSales(){
        $mallsIds = getMallsIds();
        if(count($mallsIds) > 1){
            $mallsSales = new MallsSalesDatatable;
            $notifications = new Notifications();
            return $mallsSales->with('mallsIds', $mallsIds)->render('mall_manager.sales.index', ['title' => trans('admin.slaes'), 'notifications' => $notifications]);
        }
        return redirect('mall-manager/sales/mall-sales/' . $mallsIds->first());
    }

    public function mallSales($id){
        if(in_array($id, getMallsIds())){
            $mallSales = new MallSalesDatatable;
            $notifications = new Notifications();
            return $mallSales->with('id', $id)->render('mall_manager.sales.index', ['title' => trans('admin.mall_sales'), 'notifications' => $notifications]);
        }
        return redirect('mall-manager/sales');
    }
}