<?php

// update product_coast in bill_products table and update total_coast in bill table
//if the the offer is end and product_coast still equal price_offer in bill and bill_products
//or update it's after the offer is start and the product in bill
if(!function_exists('updatePriceBill')){
  function updatePriceBill($billProductId , $price , $quantity , $billId , $product_coast , $total_coast){
    $oldTotal = $product_coast*$quantity;
    $newTotal = $price*$quantity;

    $total_coast = $total_coast - $oldTotal;
    $total_coast = $total_coast + $newTotal;

    App\Bill::find($billId)->update(['total_coast'=>$total_coast]);
    App\BillProduct::find($billProductId)->update(['product_coast'=>$price]);
  }
}

if(!function_exists('getWebsiteInfo')){
	function getWebsiteInfo(){
        $info = \App\WebSiteInfo::orderBy('id', 'desc')->with('attrInfo')->first();
        if(!isset($info)){
            $seeder = new \WebSiteInfoSeeder();
            $seeder->run();
            $info = \App\WebSiteInfo::orderBy('id', 'desc')->first();
        }
        return $info;
    }
}

if(!function_exists('getNiceNames')){
	function getNiceNames($array = [], $input){
        $niceNames = [];
        foreach ((array) $array as $key => $value) {
            $niceNames[$input . '.' . $key] = trans('admin.' . $input) . ' ' . '[' . ($key + 1) . ']';
        }
        return $niceNames;
    }
}

if(!function_exists('getSubTotal')){
	function getSubTotal($productsInBill){
		$subtotal = 0;
		foreach ($productsInBill as $key => $productInBill) {
			$subtotal += $productInBill->product_coast * $productInBill->quantity;
		}
		return $subtotal;
	}
}

if(!function_exists('getBillMalls')){
	function getBillMalls($productsInBill){
		$malls = [];
		foreach ($productsInBill as $key => $productInBill) {
			$mall = $productInBill->mall->{'name_' . lang()};
			if(!in_array($mall, $malls)){
				$malls[] = $mall;
			}
		}
		return implode(',', $malls);
	}
}

if(!function_exists('getCountries')){
	function getCountries(){
		$countries = [];
		foreach (auth()->guard('web')->user()->malls as $key => $mall) {
			!in_array($mall->country, $countries) ? $countries[] = $mall->country : '';
		}
		return $countries;
	}
}

if(!function_exists('getMallsIds')){
	function getMallsIds(){
		$mallsIds = [];
		foreach (auth()->guard('web')->user()->malls as $key => $mall) {
			$mallsIds[] = $mall->id;
		}
		return $mallsIds;
	}
}

if(!function_exists('hasMalls')){
	function hasMalls(){
		return count(auth()->guard('web')->user()->malls)  > 0;
	}
}

if(!function_exists('activeList')){
	function activeList($keyword, $segment = 2){
		$url = request()->segment($segment);
		return $url === $keyword ? 'display: block;' : '';
	}
}

if(!function_exists('activeLink')){
	function activeLink($url){
		$urlCurrent = url()->current();
		return $url === $urlCurrent ? 'active' : '';
	}
}

if(!function_exists('isManager')){
	function isManager(){
		return auth()->guard('web')->check() && auth()->guard('web')->user()->level === 'mall';
	}
}

if(!function_exists('isCompany')){
	function isCompany(){
		return auth()->guard('web')->check() && auth()->guard('web')->user()->level === 'company';
	}
}

if(!function_exists('isAdmin')){
	function isAdmin(){
		return auth()->guard('admin')->check();
	}
}

if(!function_exists('get_parents')){
	function get_parents($depId){
		$department = App\Department::where('id', $depId)->first();
		if($department->parent !== null && $department->parent > 0){
			return get_parents($department->parent) . ',' . $department->id;
		}
		return $department->id;
	}
}

if(!function_exists('load_dep')){
	function load_dep($select = null, $depId = null, $mallId = null){

		$departments = App\Department::selectRaw('name_' . lang() . ' as text')
		->selectRaw('departments.id as id')
		->selectRaw('parent as parent')
		->selectRaw('is_active as status')
		->when($mallId !== null, function($query) use ($mallId){
			return $query->join('mall_departments', 'mall_departments.department_id', 'departments.id')
						->where('mall_departments.mall_id', $mallId)->distinct();
		})
		->when( isManager() && $mallId === null, function ($query) {
	        return $query->where('owner', auth()->guard('web')->user()->id)
	        			 ->orWhere([['owner', 'admin'], ['is_active', 'active']])
	        			 ->orWhere([['is_active', 'active'], ['owner', '!=', auth()->guard('web')->user()->id]]);
	    })
		->get(['text', 'id', 'parent']);

		$departmentsIds = explode(',', $departments->implode('id', ','));

		$dep_arr = [];

		foreach($departments as $department){
			$list_arr = [];
			$list_arr['icon'] = '';
			$list_arr['li_attr'] = '';
			$list_arr['a_attr'] = '';
			$list_arr['children'] = [];

			if($select !== null && (is_array($select) && in_array($department->id, $select)) || (!is_array($select) && $select == $department->id)){
				
				$list_arr['state'] = [
					'opened' => true,
					'selected' => true,
				];
			}

			if($depId !== null && $depId == $department->id){
				$list_arr['state'] = [
					'opened' => false,
					'selected' => false,
					'disabled' => true,
					'hidden' => true,
				];
			}

			$isActive = isAdmin() && $department->status === 'inactive' ? '    <span style="color: red;">inactive</span>' : '';

			$list_arr['id'] = $department->id;
			$list_arr['parent'] = ($mallId !== null && $department->parent !== null && !in_array($department->parent, $departmentsIds)) || $department->parent === null ? '#' : $department->parent;
			$list_arr['text'] = $department->text . $isActive;
			array_push($dep_arr, $list_arr);
		}

		return json_encode($dep_arr, JSON_UNESCAPED_UNICODE);
	}
}

if(!function_exists('settings')){
	function settings(){
		return App\Setting::orderBy('id', 'desc')->first();
	}
}

if(!function_exists('makeSettings')){
	function makeSettings(){
		$settings = new \App\Setting();
		$settings->sitename_ar = 'بازار السيب';
		$settings->sitename_en = 'Bazar Al Seeb';
		$settings->email = 'bazaralseeb@gmail.com';
		$settings->description = 'Web Site For All Needs Of Mamies';
		$settings->keywords = 'Web,Site,For,All,Needs';
		$settings->message_maintenance = 'Web Site Is Under Maintenance';
		$settings->save();
	}
}

if(!function_exists('lang')){
	function lang(){
		if(session()->has('lang')){
			return session('lang');
		}
		else{
			if(settings() === null){
				makeSettings();
			}
			return settings()->main_lang;
		}
	}
}

if(!function_exists('direction')){
	function direction(){
		if(lang() == 'en'){
			return 'ltr';
		}
		else{
			return 'rtl';
		}
	}
}