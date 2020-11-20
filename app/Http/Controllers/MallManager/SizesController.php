<?php

namespace App\Http\Controllers\MallManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\SizesDatatable;
use App\Size;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class sizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SizesDatatable $sizes)
    {
        $notifications = new Notifications();
        return $sizes->render('mall_manager.sizes.index', ['title' => trans('admin.sizes_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('mall_manager.sizes.create', ['notifications' => $notifications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'department_id' => 'required|numeric',
            'is_public' => 'required|in:yes,no',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        Size::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/sizes');
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
        $allowedSizes = $this->getAllowedSizes();
        if(!$this->checkSizeExist($id, $allowedSizes)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/sizes');
        }

        $notifications = new notifications();

        $size = Size::find($id);
        return view('mall_manager.sizes.edit', ['notifications' => $notifications, 'size' => $size]);
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
        $allowedSizes = $this->getAllowedSizes();
        if(!$this->checkSizeExist($id, $allowedSizes)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/sizes');
        }

        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'department_id' => 'required|numeric',
            'is_public' => 'required|in:yes,no',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        Size::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/sizes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allowedSizes = $this->getAllowedSizes();
        if(!$this->checkSizeExist($id, $allowedSizes)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/sizes');
        }

        $size = Size::find($id);
        $size->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        $allowedSizes = $this->getAllowedSizes();
        foreach(request('delete') as $id){
            if(!$this->checkSizeExist($id, $allowedSizes)){
                session()->flash('error', trans('admin.manager_delete_or_update_error'));
                return redirect('mall-manager/sizes');
            }

            $size = Size::find($id);
            $size->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }

    private function getAllowedSizes(){
        $allowedSizes = Size::selectRaw('id as id')
                                         ->where([['owner', auth()->guard('web')->user()->id]])
                                         ->get('id');
        return $allowedSizes;
    }

    private function checkSizeExist($id, $allowedSizes){
        foreach ($allowedSizes as $key => $size) {
            if($size->id == $id){
                return true;
            }
        }
        return false;
    }
}
