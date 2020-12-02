<?php

namespace App\Http\Controllers\MallManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageOptimizer;
use App\DataTables\ProductsDatatable;
use App\Product;
use App\File;
use App\Weight;
use App\Size;
use App\OtherData;
use App\MallProduct;
use App\SizeProduct;
use App\ColorProduct;
use App\MallDepartment;
use Up;
use DB;
use Storage;
use Carbon\Carbon;
use App\Rules\KeysEqualArray;
use App\Http\Controllers\Notifications;

class productsController extends Controller
{
    public function __construct()
    {
       $this->middleware('checkMalls');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDatatable $products)
    {
        $this->deleteEmptyProduct();
        $notifications = new Notifications();
        return $products->render('mall_manager.products.index', ['title' => trans('admin.products_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->deleteEmptyProduct();

        $nextId = DB::table('products')->insertGetId(['status' => 'active', 'created_at' => date_create(), 'updated_at' => date_create()]);
        session()->put('pId', $nextId);
        return redirect('mall-manager/products/' . $nextId . '/edit');

        //return view('mall_manager.products.product', ['pid' => $nextId]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->deleteEmptyProduct();

        $sizesQuantitiesRequired = request()->has('sizes') ? 'required' : 'nullable';
        $colorsQuantitiesRequired = request()->has('colors') ? 'required' : 'nullable';

        $sizesNiceNames = getNiceNames(request('sizes_quantities'), 'sizes_quantities');
        $colorsNiceNames = getNiceNames(request('colors_quantities'), 'colors_quantities');
        $mallsNiceNames = getNiceNames(request('malls'), 'malls');
        $niceNames = array_merge($sizesNiceNames, $colorsNiceNames, $mallsNiceNames);

        $data = $this->validate(request(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            'photo' => $photoRequired . '|image',
            'content' => 'required',
            'department_id' => 'required|numeric',
            'trade_id' => 'sometimes|nullable|numeric',
            'manu_id' => 'sometimes|nullable|numeric',
            'country_id' => 'sometimes|nullable|numeric',
            'size' => 'sometimes|nullable',
            'weight_id' => 'sometimes|nullable|numeric',
            'product_weight' => 'sometimes|nullable',
            'stock' => 'required|numeric',
            'start_at' => 'sometimes|nullable|date',
            'end_at' => 'sometimes|nullable|date',
            'offer_start_at' => 'sometimes|nullable|date',
            'offer_end_at' => 'sometimes|nullable|date',
            'price_offer' => 'sometimes|nullable|numeric',
            'price' => 'required|numeric',
            'reason' => 'sometimes|nullable',
            'malls' => 'required|array',
            'malls.*' => 'required|numeric',
            'sizes_quantities' => [$sizesQuantitiesRequired, 'array', new KeysEqualArray(request('sizes'))],
            'sizes_quantities.*' => $sizesQuantitiesRequired . '|numeric',
            'colors_quantities' => [$colorsQuantitiesRequired, 'array', new KeysEqualArray(request('colors'))],
            'colors_quantities.*' => $colorsQuantitiesRequired . '|numeric',
        ], [], $niceNames);

        unset($data['sizes_quantities']);
        unset($data['colors_quantities']);

        $data['created_at'] = date_create();
        $data['updated_at'] = date_create();

        $nextId = $this->getNextID('products');

        if(request()->hasFile('photo')){
            $data['photo'] = Up::upload([
                'path' => 'products/' . $nextId,
                'uploadType' => 'single',
                'file' => 'photo',
                'deleteFile' => ''
            ]);
        }

        $data['status'] = 'active';
        $data['reason'] = '';
        $data = $this->date($data);
        
        unset($data['malls']);
        
        $id = DB::table('products')->insertGetId($data);

        if(request()->has('other_data')){
            $this->storeOtherData(request('other_data'), $id);
        }

        if(request()->has('malls')){
            $this->linkDepartmentMall(request('malls'), request('department_id'));
            $this->createOrUpdateBelongings('mall_products', false, request('malls'), $id, 'product_id', 'mall_id');
        }

        if(request()->has('colors')){
            $this->createOrUpdateBelongings('color_products', false, request('colors'), $id, 'product_id', 'color_id', request('colors_quantities'));
        }

        if(request()->has('sizes')){
            $this->createOrUpdateBelongings('size_products', false, request('sizes'), $id, 'product_id', 'size_id', request('sizes_quantities'));
        }

        if(request()->ajax()){
            return response(['status' => true, 'message' => 'Prodact Saved'], 200);
        }

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/products');
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
        $allowedProducts = $this->getAllowedProducts();
        if(!$this->checkProductExist($id, $allowedProducts) && session('pId') != $id){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/products');
        }

        $product = Product::where('id', $id)->with('otherData')->first();
        $table = $this->setOtherDataTable($product->otherData);
        $notifications = new Notifications();
        return view('mall_manager.products.product', ['notifications' => $notifications, 'table' => $table, 'product' => $product]);
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
        $allowedProducts = $this->getAllowedProducts();
        if(!$this->checkProductExist($id, $allowedProducts) && session('pId') != $id){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/products');
        }
        $sizesQuantitiesRequired = request()->has('sizes') ? 'required' : 'nullable';
        $colorsQuantitiesRequired = request()->has('colors') ? 'required' : 'nullable';

        $sizesNiceNames = getNiceNames(request('sizes_quantities'), 'sizes_quantities');
        $colorsNiceNames = getNiceNames(request('colors_quantities'), 'colors_quantities');
        $mallsNiceNames = getNiceNames(request('malls'), 'malls');
        $niceNames = array_merge($sizesNiceNames, $colorsNiceNames, $mallsNiceNames);

        $data = $this->validate(request(), [
            'name_en' => 'required',
            'name_ar' => 'required',
            'photo' => 'required|string',
            'content' => 'required',
            'department_id' => 'required|numeric',
            'trade_id' => 'sometimes|nullable|numeric',
            'manu_id' => 'sometimes|nullable|numeric',
            'country_id' => 'sometimes|nullable|numeric',
            'size' => 'sometimes|nullable',
            'weight_id' => 'sometimes|nullable|numeric',
            'product_weight' => 'sometimes|nullable',
            'stock' => 'required|numeric',
            'start_at' => 'sometimes|nullable|date',
            'end_at' => 'sometimes|nullable|date',
            'offer_start_at' => 'sometimes|nullable|date',
            'offer_end_at' => 'sometimes|nullable|date',
            'price_offer' => 'sometimes|nullable|numeric',
            'price' => 'required|numeric',
            'reason' => 'sometimes|nullable',
            'malls' => 'required|array',
            'malls.*' => 'required|numeric',
            'sizes_quantities' => [$sizesQuantitiesRequired, 'array', new KeysEqualArray(request('sizes'))],
            'sizes_quantities.*' => $sizesQuantitiesRequired . '|numeric',
            'colors_quantities' => [$colorsQuantitiesRequired, 'array', new KeysEqualArray(request('colors'))],
            'colors_quantities.*' => $colorsQuantitiesRequired . '|numeric',
        ], [], $niceNames);

        unset($data['sizes_quantities']);
        unset($data['colors_quantities']);

        $product = Product::find($id);

        if(session()->has('pId') && session('pId') == $id){
            session()->forget('pId');
        }

        if($product->photo !== request('photo')){
            if(request()->ajax()){
                return response(['status' => false, 'message' => trans('admin.product_photo_error')], 200);
            }
            return back()->with('error', trans('admin.product_photo_error'));
        }

        $productStatus = $product->status;
        $productReason = $product->reason;
        $data['status'] = $productStatus;
        $data['reason'] = $productReason;

        $data = $this->date($data);

        if(request()->hasFile('photo')){
            $data['photo'] = Up::upload([
                'path' => 'products/' . $id,
                'uploadType' => 'single',
                'file' => 'photo',
                'deleteFile' => $product->photo,
            ]);
        }

        OtherData::where('product_id', $id)->delete();
        if(request()->has('other_data')){
            $this->storeOtherData(request('other_data'), $id);
        }

        if(request()->has('malls')){
            $this->linkDepartmentMall(request('malls'), request('department_id'));
            $this->createOrUpdateBelongings('mall_products', true, request('malls'), $id, 'product_id', 'mall_id');
        }

        if(request()->has('colors')){
            $this->createOrUpdateBelongings('color_products', true, request('colors'), $id, 'product_id', 'color_id', request('colors_quantities'));
        }

        if(request()->has('sizes')){
            $this->createOrUpdateBelongings('size_products', true, request('sizes'), $id, 'product_id', 'size_id', request('sizes_quantities'));
        }

        unset($data['malls']);

        $product->update($data);

        if(request()->ajax()){
            return response(['status' => true, 'message' => 'Prodact Saved'], 200);
        }

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allowedProducts = $this->getAllowedProducts();
        if(!$this->checkProductExist($id, $allowedProducts) && session('pId') != $id){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/products');
        }

        session()->flash('success', trans('admin.record_deleted_successfully'));

        $product = Product::find($id);
        if($this->checkOtherMalls($product)) return redirect('mall-manager/products');

        Storage::deleteDirectory('products/' . $id);
        foreach (File::where([['relationId', '=', $id], ['fileType', '=', 'product']])->get() as $key => $file) {
            $file->delete();
        }
        $product->delete();

        return redirect('mall-manager/products');
    }

    public function multiDelete(){
        $allowedProducts = $this->getAllowedProducts();
        foreach(request('delete') as $id){
            if(!$this->checkProductExist($id, $allowedProducts)){
                session()->flash('error', trans('admin.manager_delete_or_update_error'));
                return redirect('mall-manager/products');
            }

            $product = Product::find($id);
            if($this->checkOtherMalls($product)) continue;

            Storage::deleteDirectory('products/' . $id);
            foreach (File::where([['relationId', '=', $id], ['fileType', '=', 'product']])->get() as $key => $file) {
                $file->delete();
            }
            $product->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return redirect('mall-manager/products');
    }

    public function copyProduct($id){
        $allowedProducts = $this->getAllowedProducts();
        if(!$this->checkProductExist($id, $allowedProducts)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/products');
        }

        if(request()->ajax()){
            $productCopy = Product::find($id)->toArray();
            $productCopyId = $productCopy['id'];
            unset($productCopy['id']);
            $nextId = $this->getNextID('products');
            $ext = \File::extension($productCopy['photo']);
            $path = 'products/' . $nextId . '/' . str_random(30) . '.' . $ext;
            Storage::copy($productCopy['photo'], $path);
            $productCopy['photo'] = $path;
            $newProduct = Product::create($productCopy);

            $this->copyFiles($productCopyId, $newProduct->id);

            $this->copyBelongings($productCopyId, $newProduct->id);
            
            return response(['status' => true, 'message' => 'Product Copied', 'id' => $newProduct->id], 200);
        }
        else{
            return redirect('mall-manager/home');
        }
    }

    public function uploadFiles($pid){
        if(request()->hasFile('file')){
            $id = Up::upload([
                'path' => 'products/' . $pid,
                'uploadType' => 'multiple',
                'relationId' => $pid,
                'file' => 'file',
                'fileType' => 'product'
            ]);
            return $id;
        }
    }

    public function deleteFile(){

        $this->validate(request(), [
            'id' => 'required|numeric'
        ]);

        $file = File::find(request('id'));
        if(!empty($file)){
            Storage::delete($file->fullFile);
            $file->delete();
        }
    }

    public function uploadPhoto($pId){
        if(request()->hasFile('file')){
            $product = Product::find($pId);
            $optimizedPhoto = ImageOptimizer::storeImage(request()->file('file'), 'products/' . $pId, $product->optimized_photo);
            $photo = Up::upload([
                'path' => 'products/' . $pId,
                'uploadType' => 'single',
                'file' => 'file',
                'deleteFile' => $product->photo
            ]);
            $product->update(['photo' => $photo, 'optimized_photo' => $optimizedPhoto]);
            return $photo;
        }
    }

    public function deletePhoto(){
        $this->validate(request(), [
            'id' => 'required|numeric',
        ]);

        $product = Product::find(request('id'));
        if(!empty($product)){
            Storage::delete($product->photo);
            Storage::delete($product->optimized_photo);
            $product->update(['photo' => null, 'optimized_photo' => null]);
            return $product->id;
        }
    }

    private function deleteEmptyProduct(){
        if(session()->has('pId')){
            $this->destroy(session('pId'));
            session()->forget('pId');
            session()->forget('success');
        }
    }

    public function getShippInfo(){
        $this->validate(request(), [
            'id' => 'required|numeric',
            'productId' => 'required|numeric',
        ]);

        if(request()->ajax()){
            $sizes = Size::where('owner', auth()->guard('web')->user()->id)
                            ->orWhere('owner', 'admin')
                            ->where(function($query){
                                return $query->where('is_public', 'yes')
                                        ->whereIn('department_id', explode(',', get_parents(request('id'))))
                                        ->orWhere([['is_public', '=', 'no'], ['department_id', '=', request('id')]]);
                            })
                            ->get();
            $weights = Weight::where('owner', auth()->guard('web')->user()->id)
                                ->orWhere('owner', 'admin')
                                ->pluck('name_' . lang(), 'id');
                                
            $sizesJson = $this->getSizesJson($sizes, request('productId'));

            return response()->json(['sizes' => $sizesJson, 'weights' => view('mall_manager.products.shipp_info_ajax', ['weights' => $weights, 'productId' => request('productId')])->render()]);
        }
    }

    private function getSizesJson($sizes, $productId){

        $sizesOptions = [];
        foreach ($sizes as $key => $size) {
            $sizeData = [
                'id' => $size->id,
                'text' => $size->{'name_' . lang()},
            ];
            if($productId > 0){
              $sizeData['selected'] = SizeProduct::where([['product_id', $productId], ['size_id', $size->id]])->count() > 0 ? true : false;
            }
            $sizesOptions[] = $sizeData;
        }

        return response()->json(['sizes' => $sizesOptions]);
    }

    private function getQuantity($quantities = [], $id){
        foreach ($quantities as $key => $quantity) {
            if($key == $id){
                return $quantity;
            }
        }
        return 0;
    }

    function deleteBelongings($table, $relationProduct, $productId){
        $mallsIds = getMallsIds();
        DB::table($table)
            ->where($relationProduct, $productId)
            ->when($table == 'mall_products', function($query) use ($mallsIds){
                return $query->whereIn('mall_id', $mallsIds);
            })
            ->delete();
    }

    private function createOrUpdateBelongings($table, $isUpdate, $request = [], $productId, $relationProduct, $relationTable, $quantities = null){
        $isUpdate ? $this->deleteBelongings($table, $relationProduct, $productId) : '';
        $data = [];
        foreach ($request as $key => $value) {
            $row = [
                $relationProduct => $productId,
                $relationTable => $value,
                'created_at' => date_create(),
                'updated_at' => date_create(),
            ];
            
            if($quantities !== null) $row = array_merge($row, ['quantity' => $this->getQuantity((array) $quantities, $value)]);
            $data[] = $row;
        }
        DB::table($table)->insert($data);
    }

    private function getNextID($table){
        $statement = DB::select("SHOW TABLE STATUS LIKE '" . $table . "'");
        $nextId = $statement[0]->Auto_increment;
        return $nextId;
    }

    private function copyFiles($productCopyId, $newProductId){
        $files = File::where([['fileType', '=', 'product'], ['relationId', '=', $productCopyId]])->get();
        foreach ($files as $key => $file) {
            $file = $file->toArray();
            unset($file['id']);
            $ext = \File::extension($file['file']);
            $name = str_random(30) . '.' . $ext;
            $path = 'products/' . $newProductId . '/' . $name;
            Storage::copy($file['fullFile'], $path);
            $file['file'] = $name;
            $file['fullFile'] = $path;
            $file['relationId'] = $newProductId;
            $file['created_at'] = date_create();
            $file['updated_at'] = date_create();
            File::create($file);
        }
    }

    private function copyBelongings($productCopyId, $newProductId){
        $sizes = SizeProduct::where('product_id', $productCopyId)->get();
        $this->copyRelation('size_products', $sizes, $newProductId, 'size_id');
        
        $colors = ColorProduct::where('product_id', $productCopyId)->get();
        $this->copyRelation('color_products', $colors, $newProductId, 'color_id');

        $malls = MallProduct::where('product_id', $productCopyId)->get();
        $this->copyRelation('mall_products', $malls, $newProductId, 'mall_id');

        $this->copyOtherData($productCopyId, $newProductId);
    }

    private function copyRelation($table, $modelValues, $productId, $relationTable){
        $data = [];
        foreach ($modelValues as $key => $value) {
            $row = [
                'product_id' => $productId,
                $relationTable => $value->{$relationTable},
                'created_at' => date_create(),
                'updated_at' => date_create(),
            ];
            
            if(isset($value->quantity)) $row = array_merge($row, ['quantity' => $value->quantity]);
            $data[] = $row;
        }
        DB::table($table)->insert($data);
    }

    private function copyOtherData($productCopyId, $newProductId){
        $otherData = OtherData::where('product_id', $productCopyId)->get();
        $allData = [];
        foreach ($otherData as $key => $data) {
            $row = [
                'product_id' => $newProductId,
                'row' => $data->row,
                'column' => $data->column,
                'text' => $data->text,
                'rowspan' => $data->rowspan,
                'colspan' => $data->colspan,
                'created_at' => date_create(),
                'updated_at' => date_create(),
            ];
            $allData[] = $row;
        }
        DB::table('other_datas')->insert($allData);
    }

    private function date($data = []){
        $data['start_at'] = Carbon::parse($data['start_at']);
        $data['end_at'] = Carbon::parse($data['end_at']);
        $data['offer_start_at'] = Carbon::parse($data['offer_start_at']);
        $data['offer_end_at'] = Carbon::parse($data['offer_end_at']);
        return $data;
    }

    private function getAllowedProducts(){
        $malls = auth()->guard('web')->user()->malls;
        $allowedProducts = [];
        foreach ($malls as $key => $mall) {
            foreach ($mall->products as $key => $productInMall) {
                $allowedProducts[] = $productInMall->product->id;
            }
        }
        return $allowedProducts;
    }

    private function checkProductExist($id, $allowedProducts){
        return in_array($id, $allowedProducts);
    }

    private function linkDepartmentMall($mallsIds = [], $depId){
        $mallDepartments = MallDepartment::whereIn('mall_id', $mallsIds)->where('department_id', $depId)->get();
        $mallsIdsFromDb = $mallDepartments->implode('mall_id', ',');
        $mallsIdsFromDb = explode(',', $mallsIdsFromDb);
        foreach ($mallsIds as $key => $mallId) {
            if(!in_array($mallId, $mallsIdsFromDb)){
                MallDepartment::create([
                    'mall_id' => $mallId,
                    'department_id' => $depId,
                ]);
            }
        }
    }

    private function checkOtherMalls($product){
        $productMalls = explode(',', $product->malls->implode('mall_id', ','));
        $deff = array_diff(array_values($productMalls), array_values(getMallsIds()));
        if(count($deff) > 0){
            $this->deleteBelongings('mall_products', 'product_id', $product->id);
            return true;
        }
        return false;
    }

    private function storeOtherData($request, $id){
        $data = [];
        foreach ($request as $key => $value) {
            $details = explode(',', $value);
            $record = [
                'product_id' => $id,
                'row' => $details[0],
                'column' => $details[1],
                'text' => $details[2],
                'rowspan' => $details[3],
                'colspan' => $details[4],
                'created_at' => date_create(),
                'updated_at' => date_create(),
            ];
            if(!(is_numeric($record['row']) && is_numeric($record['column']) && is_numeric($record['rowspan']) && is_numeric($record['colspan']))) return false;
            $data[] = $record;
        }
        DB::table('other_datas')->insert($data);
    }

    private function addCell($data){
        $cell = [
            'text' => $data->text,
            'rowspan' => $data->rowspan,
            'colspan' => $data->colspan,
        ];
        return $cell;
    }

    private function addColumn($rows, $data){
        foreach ($rows as $key => $row) {
            if($key == $data->row) {
                $rows[$data->row][] = $this->addCell($data);
                return $rows;
            };
        }
        $rows[$data->row] = [$this->addCell($data)];
        return $rows;
    }

    private function setOtherDataTable($otherData){
        $rows = [];
        foreach ($otherData as $key => $data) {
            $rows = $this->addColumn($rows, $data);
        }
        return $rows;
    }
}