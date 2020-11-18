<?php

namespace App\Http\Controllers\MallManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\AddsDatatable;

use App\Ad;
use App\MallProduct;
use App\Product;
use App\Department;
use App\MallDepartment;
use App\AdDepartment;
use App\AdProduct;
use Up;
use DB;
use Carbon\Carbon;
use Storage;
use App\Http\Controllers\Notifications;

class AddsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware('checkMalls');
    }


    public function index(AddsDatatable $adds)
    {
    	$title = trans('admin.adds_control');
        $notifications = new Notifications();
    	return $adds->render('mall_manager.adds.index', ['title' => $title, 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = trans('admin.create_add');
        $mallsIds = getMallsIds();
        $notifications = new Notifications();
    	return view('mall_manager.adds.create', ['title' => $title, 'mallsIds' => $mallsIds, 'notifications' => $notifications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$discountRequired = request()->has('discountRadio') && request('discountRadio') == 'yes' ? 'required' : 'sometimes|nullable';
        $productsDepartmentsRequired = request()->has('discountRadio') && request('discountRadio') == 'yes' ? 'required' : 'sometimes|nullable';
        $departmentRequired = request()->has('productsDepartments') && request('productsDepartments') == 'departments' ? 'required' : 'sometimes|nullable';
        $productsRequired = request()->has('productsDepartments') && request('productsDepartments') == 'products' ? 'required' : 'sometimes|nullable';
        
        $productsNiceNames = getNiceNames(request('products'), 'products');
        $departmentsNiceNames = getNiceNames(request('departments_ids'), 'departments_ids');
        $niceNames = array_merge($productsNiceNames, $departmentsNiceNames);

        $data = $this->validate(request(), [
        	'title_ar' => 'required',
        	'title_en' => 'required',
        	'photo' => 'required|image|mimes:jpg,png,jpeg,gif',
        	'start_at' => 'sometimes|nullable|date',
        	'end_at' => 'sometimes|nullable|date',
        	'ad' => 'required',
        	'mall_id' => 'required|numeric|in:' . implode(getMallsIds(), ','),
        	'discountRadio' => 'required|in:yes,no',
        	'discount' => $discountRequired . '|numeric|min:1',
        	'productsDepartments' => $productsDepartmentsRequired . '|in:products,departments',
        	'departments_ids' => $departmentRequired . '|array',
        	'departments_ids.*' => $departmentRequired . '|numeric|in:' . $this->getMallDepartments(),
        	'products' => $productsRequired . '|array',
        	'products.*' => $productsRequired . '|numeric|in:' . $this->getAllowedProductsIds(),
        ], [], $niceNames);

        unset($data['discountRadio']);
        unset($data['productsDepartments']);
        unset($data['products']);
        unset($data['departments_ids']);

        if(request()->hasFile('photo')){
        	$data['photo'] = Up::upload([
        		'file' => 'photo',
        		'uploadType' => 'single',
        		'path' => 'adds',
        		'deleteFile' => '',
        	]);
        }

        $data = $this->date($data);

        $add = Ad::create($data);

        if(request('discountRadio') === 'yes'){
        	if(request('productsDepartments') === 'products'){
        		$this->updateProducts(request('products'), request('discount'), $data['start_at'], $data['end_at'], $add->id);
        	}
        	else{
        		$this->updateProductsDepartments(request('departments_ids'), request('discount'), $data['start_at'], $data['end_at'], $data['mall_id'], $add->id);
        	}
        }

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/adds');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$title =trans('admin.edit_add');
    	$add = Ad::find($id);
    	$departments = AdDepartment::where('ad_id', $add->id)->get();
    	$products = AdProduct::where('ad_id', $add->id)->get();
        $mallsIds = getMallsIds();

        $notifications = new notifications();

    	return view('mall_manager.adds.edit', ['title' => $title, 'add' => $add, 'products' => $products, 'departments' => $departments, 'mallsIds' => $mallsIds, 'notifications' => $notifications]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $discountRequired = request()->has('discountRadio') && request('discountRadio') == 'yes' ? 'required' : 'sometimes|nullable';
        $productsDepartmentsRequired = request()->has('discountRadio') && request('discountRadio') == 'yes' ? 'required' : 'sometimes|nullable';
        $departmentRequired = request()->has('productsDepartments') && request('productsDepartments') == 'departments' ? 'required' : 'sometimes|nullable';
        $productsRequired = request()->has('productsDepartments') && request('productsDepartments') == 'products' ? 'required' : 'sometimes|nullable';
        
        $productsNiceNames = getNiceNames(request('products'), 'products');
        $departmentsNiceNames = getNiceNames(request('departments_ids'), 'departments_ids');
        $niceNames = array_merge($productsNiceNames, $departmentsNiceNames);

        $data = $this->validate(request(), [
        	'title_ar' => 'required',
        	'title_en' => 'required',
        	'photo' => 'sometimes|nullable|image|mimes:jpg,png,jpeg,gif',
        	'start_at' => 'sometimes|nullable|date',
        	'end_at' => 'sometimes|nullable|date',
        	'ad' => 'required',
        	'mall_id' => 'required|numeric|in:' . implode(getMallsIds(), ','),
            'discountRadio' => 'required|in:yes,no',
            'discount' => $discountRequired . '|numeric|min:1',
            'productsDepartments' => $productsDepartmentsRequired . '|in:products,departments',
            'departments_ids' => $departmentRequired . '|array',
            'departments_ids.*' => $departmentRequired . '|numeric|in:' . $this->getMallDepartments(),
            'products' => $productsRequired . '|array',
            'products.*' => $productsRequired . '|numeric|in:' . $this->getAllowedProductsIds(),
        ], [], $niceNames);

        unset($data['discountRadio']);
        unset($data['productsDepartments']);
        unset($data['products']);
        unset($data['departments_ids']);

        if(request()->hasFile('photo')){
        	$data['photo'] = Up::upload([
        		'file' => 'photo',
        		'uploadType' => 'single',
        		'path' => 'adds',
        		'deleteFile' => '',
        	]);
        }

        $data = $this->date($data);

        $add = Ad::find($id);
        $mallId = $add->mall_id;
        $add->update($data);

        if(request('discountRadio') === 'yes'){
        	$this->deleteBelongings($id, $mallId);
        	if(request('productsDepartments') === 'products'){
        		$this->updateProducts(request('products'), request('discount'), $data['start_at'], $data['end_at'], $id);
        	}
        	else{
        		$this->updateProductsDepartments(request('departments_ids'), request('discount'), $data['start_at'], $data['end_at'], $data['mall_id'], $id);
        	}
        }

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/adds');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $add = Ad::find($id);
        $this->deleteBelongings($add->id, $add->mall_id);
        Storage::has($add->photo) ? Storage::delete($add->photo) : '';
        $add->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return redirect('mall-manager/adds');
    }

    public function multiDelete(){
    	$this->validate(request(), [
    		'delete' => 'required|array',
    		'delete.*' => 'required|numeric',
    	]);

    	foreach (request('delete') as $key => $id) {
    		$this->destroy($id);
    	}

    	session()->flash('success', trans('admin.records_deleted_successfully'));
        return redirect('mall-manager/adds');
    }

    public function getData(){
    	$this->validate(request(), [
    		'choose' => 'required|string|in:products,departments',
    		'mallId' => 'required|numeric|in:' . implode(',', getMallsIds()),
    		'departments_ids' => 'sometimes|nullable|array',
    		'departments_ids.*' => 'sometimes|nullable|numeric|in:' . $this->getMallDepartments(),
    	]);

    	$data = [];

    	if(request('choose') == 'products'){
    		$mallProducts = MallProduct::where('mall_id', request('mallId'))
    							->with('product')
    							->get();
    		$data = response()->json(['type' => 'pro', 'input' => view('mall_manager.adds.ajax.products', ['mallProducts' => $mallProducts])->render()]);
    	}
    	else{
    		$departments = $this->getDepartments(request('departments_ids'), request('mallId'));
    		$data = response()->json(['type' => 'dep', 'departments' => $departments, 'input' => view('mall_manager.adds.ajax.departments')->render()]);
    	}

    	if(request()->ajax()){
    		return $data;
    	}
    }

    private function getDepartments($departmentsIds, $mallId){
    	return load_dep($departmentsIds, null, $mallId);
    }

    private function date($data = []){
        $data['start_at'] = Carbon::parse($data['start_at']);
        $data['end_at'] = Carbon::parse($data['end_at']);
        return $data;
    }

    private function deleteBelongings($addId, $mallId){
    	$adProducts = AdProduct::where('ad_id', $addId)->get();
    	foreach ($adProducts as $key => $adProduct) {
    		$adProduct->product->update([
                'price_offer' => 0.00,
                'offer_start_at' => '1890-01-01 00:00:00',
                'offer_end_at' => '1890-01-01 00:00:00'
            ]);
    		$adProduct->delete();
    	}

    	$adDepartments = AdDepartment::where('ad_id', $addId)->get();

    	foreach ($adDepartments as $key => $adDepartment) {
    		foreach ($adDepartment->department->products as $key => $product) {
    			if(in_array($mallId, explode(',', $product->malls->implode('mall_id', ',')))){
    				$product->update([
                        'price_offer' => 0.00,
                        'offer_start_at' => '1890-01-01 00:00:00',
                        'offer_end_at' => '1890-01-01 00:00:00'
                    ]);
    			}
    		}
    		$adDepartment->delete();
    	}
    }

    private function setPriceOffer($products = [], $discount, $start_at, $end_at){
    	foreach ($products as $key => $product) {
    		$priceOffer = $product->price - ($product->price * $discount / 100);
    		$product->update([
    			'price_offer' => $priceOffer,
    			'offer_start_at' => $start_at,
    			'offer_end_at' => $end_at,
    		]);
    	}
    }

    private function linkDepOrPro($table, $ids = [], $relationId, $addId, $update = false){
    	$records = [];
    	foreach ($ids as $key => $id) {
    		$records[] = [
    			'ad_id' => $addId,
    			$relationId => $id,
    			'created_at' => date_create(),
    			'updated_at' => date_create(),
    		];
    	}
    	DB::table($table)->insert($records);
    }

    private function updateProducts($productsIds = [], $discount, $start_at, $end_at, $addId){
    	$products = Product::whereIn('id', $productsIds)->get();
    	$this->setPriceOffer($products, $discount, $start_at, $end_at);
    	$this->linkDepOrPro('ad_products', $productsIds, 'product_id', $addId);
    }

    private function updateProductsDepartments($departmentsIds = [], $discount, $start_at, $end_at, $mallId, $addId){
    	$departments = Department::whereIn('id', $departmentsIds)->whereHas('products', function($query) use ($mallId){
    		return $query->whereHas('malls', function($query) use ($mallId){
    			return $query->where('mall_id', $mallId);
    		});
    	})
    	->get();

    	$products = [];
    	foreach ($departments as $key => $department) {
    		foreach ($department->products as $key => $product) {
    			if(in_array($mallId, explode(',', $product->malls->implode('mall_id', ',')))){
    				$products[] = $product;
    			}
    		}
    	}

    	$this->setPriceOffer($products, $discount, $start_at, $end_at);

    	$this->linkDepOrPro('ad_departments', $departmentsIds, 'department_id', $addId);
    }

    private function getMallDepartments(){
        $departmentsIds = MallDepartment::distinct()->whereIn('mall_id', getMallsIds())->pluck('department_id');
        return substr($departmentsIds, 1, -1);
    }

    private function getAllowedProductsIds(){
        $malls = auth()->guard('web')->user()->malls;
        $allowedProducts = [];
        foreach ($malls as $key => $mall) {
            foreach ($mall->products as $key => $productInMall) {
                $allowedProducts[] = $productInMall->product->id;
            }
        }
        $allowedProducts = implode(',', $allowedProducts);
        return $allowedProducts;
    }
}