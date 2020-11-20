<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Ad;


class AdvertisementsController extends Controller
{
    public function getAdvertisements(){
        $adds =  Ad::where([['start_at', '<=', Carbon::now()->toDateString()], ['end_at', '>=', Carbon::now()->toDateString()]])->with(['mall' => function($query){return $query->with('country');}])->get();
        return json_encode($adds);
    }
}
