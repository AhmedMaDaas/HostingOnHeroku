<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;

use App\Contact;
use Mail; 

class aboutUs extends Controller
{
    public function showPage(indexClass $indexClass){
        $departmentsParents = $indexClass->getDepartmentsWithParent();
        $sumQuantityAndTotalCost = $indexClass->checkLogin();
        $websiteInfo = getWebsiteInfo();

        return view('user_layouts.about_us', [
            'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
            'active' => 'about',
            'departmentsParents' => $departmentsParents[0],
            'mainDep' => $departmentsParents[1],
            'websiteInfo' => $websiteInfo,
        ]);
    }
}
