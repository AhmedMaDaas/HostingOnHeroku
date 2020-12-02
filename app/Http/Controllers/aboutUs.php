<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Classes\indexClass;

use App\Contact;
use Mail; 

class aboutUs extends Controller
{
    public function showPage(indexClass $indexClass){
        // $departmentsParents = $indexClass->getDepartmentsWithParent2();
        // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();
        // $sumQuantityAndTotalCost = $indexClass->checkLogin();

        /*
        *
        *   this elements is necassery for all pages in web site
        */
        $arr = $indexClass->getPrimaryElementForAllPages('about');

        $websiteInfo = getWebsiteInfo();

        return view('user_layouts.about_us', array_merge_recursive($arr,[
            //'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
            //'active' => 'about',
            //'departmentsParents' => $departmentsParents,
            //'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
            'websiteInfo' => $websiteInfo,
        ]));
    }
}
