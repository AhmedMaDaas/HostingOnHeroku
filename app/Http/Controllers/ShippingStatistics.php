<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Bill;
use App\BillProduct;

class ShippingStatistics
{
    private $avg = 0;
    private $allOrders = null;
    private $billsThisMonth = 0;
    private $billsLastMonth = 0;
    private $revenue = 0;
    private $revenueThisMonth = 0;
    private $pendingOrders = 0;
    private $cancelledOrders = 0;
    private $completedOrders = 0;
    private $productsThisMonth = 0;
    private $productsLastMonth = 0;
    private $productsMonthly = null;

    public function mainStatistics(){
        $this->allOrders = DB::table('bills')->where('status', 'completed')->when(isCompany(), function($query){
                            return $query->where('visible', 1);
                        })
                        ->select(DB::raw('sum(total_coast) as salesPerMonth'),
                                 DB::raw('count(id) as oCount'),
                                 DB::raw('YEAR(created_at) year, MONTH(created_at) month'))
                        ->groupby('year','month')
                        ->get();

        $this->revenue = $this->allOrders->sum('salesPerMonth');

        $this->revenueThisMonth = $this->ordersPerMonth($this->allOrders, Carbon::now()->year, Carbon::now()->month, 'salesPerMonth');

        $this->billsThisMonth = $this->ordersPerMonth($this->allOrders, Carbon::now()->year, Carbon::now()->month, 'oCount');

        $lastMonth = Carbon::now()->month == 1 ? 12 : Carbon::now()->month - 1;
        $year = Carbon::now()->month == 1 ? Carbon::now()->year - 1 : Carbon::now()->year;

        $this->billsLastMonth = $this->ordersPerMonth($this->allOrders, $year, $lastMonth, 'oCount');

        if(count($this->allOrders)) $this->avg = $this->allOrders->avg('oCount');
    }

    public function overview(){
        $bills = DB::table('bills')->where('status', '!=', 'opened')->select('status', DB::raw('count(id) as bCount'))->when(isCompany(), function($query){
            return $query->where('visible', 1);
        })
        ->groupby('status')
        ->get();
        
        foreach ($bills as $key => $bill) {
            $this->{$bill->status . 'Orders'} = $bill->bCount;
        }
    }

    public function managerStatistics(){
        if(!isManager()){
            return 0;
        }

        $this->productsMonthly = DB::table('malls')->where('malls.user_id', auth()->guard('web')->user()->id)
                                            ->join('bill_products', 'malls.id', 'bill_products.mall_id')
                                            ->join('bills', 'bills.id', 'bill_products.bill_id')
                                            ->where('bills.status', 'completed')
                                            ->select(DB::raw('sum(quantity) as pCount'),
                                                DB::raw('sum(product_coast * quantity) as sales'),
                                                DB::raw('YEAR(bill_products.created_at) year, MONTH(bill_products.created_at) month'))
                                            ->groupBy('year','month')
                                            ->get();

        $this->avg = count($this->productsMonthly) > 0 ? $this->productsMonthly->avg('pCount') : 0;
        $this->revenueThisMonth = $this->productSoldByMonth($this->productsMonthly, Carbon::now()->year, Carbon::now()->month, 'sales');
        $this->productsThisMonth = $this->productSoldByMonth($this->productsMonthly, Carbon::now()->year, Carbon::now()->month, 'pCount');

        $lastMonth = Carbon::now()->month == 1 ? 12 : Carbon::now()->month - 1;
        $year = Carbon::now()->month == 1 ? Carbon::now()->year - 1 : Carbon::now()->year;
        $this->productsLastMonth = $this->productSoldByMonth($this->productsMonthly, $year, $lastMonth, 'pCount');

    }

    private function ordersPerMonth($orders, $year, $month, $result){
        foreach ($orders as $key => $perMonth) {
            if($perMonth->year == $year && $perMonth->month == $month){
                return $perMonth->{$result};
            }
        }
        return 0;
    }

    public function productCoastSoldByMonth($year, $month){
        foreach ($this->productsMonthly as $key => $perMonth) {
            if($perMonth->year == $year && $perMonth->month == $month){
                return $perMonth->sales;
            }
        }
        return 0;
    }

    public function productCoastSoldByYear($year){
        $sales = 0;
        foreach ($this->productsMonthly as $key => $perMonth) {
            if($perMonth->year == $year){
                $sales += $perMonth->sales;
            }
        }
        return $sales;
    }

    public function productSoldByYear($year){
        $pCount = 0;
        foreach ($this->productsMonthly as $key => $perMonth) {
            if($perMonth->year == $year){
                $pCount += $perMonth->pCount;
            }
        }
        return $pCount;
    }

    public function productSoldByMonth($productsMonthly, $year, $month, $result){
        foreach ($productsMonthly as $key => $perMonth) {
            if($perMonth->year == $year && $perMonth->month == $month){
                return $perMonth->{$result};
            }
        }
        return 0;
    }

    public function salesPerMonth($year, $month){
        foreach ($this->allOrders as $key => $ordersPerMonth) {
            if($ordersPerMonth->year == $year && $ordersPerMonth->month == $month){
                return $ordersPerMonth->salesPerMonth;
            }
        }
        return 0;
    }

    public function salesPerYear($year){
        $sales = 0;
        foreach ($this->allOrders as $key => $ordersPerMonth) {
            if($ordersPerMonth->year == $year){
                $sales += $ordersPerMonth->salesPerMonth;
            }
        }
        return $sales;
    }

    public function totalOrdersPerYear($year){
        $orders = 0;
        foreach ($this->allOrders as $key => $ordersPerMonth) {
            if($ordersPerMonth->year == $year){
                $orders += $ordersPerMonth->oCount;
            }
        }
        return $orders;
    }

    public function topCustomers(){
        $topCustomersBills = Bill::where('status', 'completed')
                        ->when(isCompany(), function($query){
                            return $query->where('visible', 1);
                        })
                        ->select(DB::raw('count(total_coast) as oCount'),
                            DB::raw('user_id as user_id'),
                            DB::raw('sum(total_coast) as total_coast'))
                        ->groupby('user_id')
                        ->orderBy('oCount', 'desc')
                        ->limit(3)
                        ->with('user')
                        ->get();

        return $topCustomersBills;
    }

    public function topStores(){
        $topStoresBills = BillProduct::query()->select('mall_id', DB::raw('sum(quantity) as pCount'), DB::raw('sum(product_coast * quantity) as sales'))
                            ->join('bills', 'bills.id', 'bill_products.bill_id')
                            ->where('bills.status', 'completed')
                            ->groupBy('mall_id')
                            ->orderBy('pCount', 'desc')
                            ->limit(3)
                            ->with('mall')
                            ->get();
                        
        return $topStoresBills;
    }

    public function getAvg(){
        return $this->avg;
    }

    public function getBillsThisMonth(){
        return $this->billsThisMonth;
    }

    public function getBillsLastMonth(){
        return $this->billsLastMonth;
    }

    public function getRevenue(){
        return $this->revenue;
    }

    public function getRevenueThisMonth(){
        return $this->revenueThisMonth;
    }

    public function getPendingOrders(){
        return $this->pendingOrders;
    }

    public function getCancelledOrders(){
        return $this->cancelledOrders;
    }

    public function getCompletedOrders(){
        return $this->completedOrders;
    }

    public function getProductsThisMonth(){
        return $this->productsThisMonth;
    }

    public function getProductsLastMonth(){
        return $this->productsLastMonth;
    }

}
