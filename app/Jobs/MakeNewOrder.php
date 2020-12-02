<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Events\NewOrder;
use App\Notification;
use App\Admin;
use App\User;
use App\Bill;
use DB;
use Batch;
use App\Product;
use App\SizeProduct;
use App\ColorProduct;

class MakeNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $billId;

    public function __construct($billId)
    {
        $this->billId = $billId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->makePending();
        $this->makeOrderNotification();
        event(new NewOrder);
    }

    private function makePending(){
        $bill = Bill::find($this->billId);
        if(isset($bill)){
            $this->updateProduct($bill->products, 'add');
            $bill->update(['status' => 'pending', 'new' => 1]);
        }
    }

    private function makeOrderNotification(){
        $companiesIds = $this->getCompaniesIds();
        $adminsIds = $this->getAdminsIds();
        $data = $this->setData($companiesIds, $adminsIds);
        DB::table('notifications')->insert($data);
    }

    private function getCompaniesIds(){
        return User::where('level', 'company')->pluck('id');
    }

    private function getAdminsIds(){
        return Admin::orderBy('id', 'asc')->pluck('id');
    }

    private function setData($companiesIds, $adminsIds){
        $dataForCompanies = $this->setDataFor($companiesIds, 'company');
        $dataForAdmins = $this->setDataFor($adminsIds, 'admin');
        $data = array_merge($dataForCompanies, $dataForAdmins);
        return $data;
    }

    public function setDataFor($ids = [], $relation){
        $data = [];
        foreach ($ids as $key => $id) {
            $data[] = [
                'owner_id' => $id,
                'relation' => $relation,
                'notification' => 'new_order_requested',
                'new' => 1,
                'created_at' => date_create(),
                'updated_at' => date_create(),
            ];
        }
        return $data;
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
