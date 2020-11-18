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
}
