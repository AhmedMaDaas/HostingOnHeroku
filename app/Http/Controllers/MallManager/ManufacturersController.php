<?php

namespace App\Http\Controllers\MallManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ManufacturersDatatable;
use App\Manufacturer;
use Up;
use Storage;
use App\Rules\PhoneNumber;
use App\Http\Controllers\Notifications;

class manufacturersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManufacturersDatatable $manufacturers)
    {
        $notifications = new Notifications();
        return $manufacturers->render('mall_manager.manufacturers.index', ['title' => trans('admin.manufacturers_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('mall_manager.manufacturers.create', ['notifications' => $notifications]);
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
            'mobile' => ['required', new PhoneNumber()],
            'email' => 'required|email',
            'address' => 'sometimes|nullable',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'website' => 'sometimes|nullable|url',
            'contact_name' => 'sometimes|nullable|string',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|image',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'manufacturers',
                'deleteFile' => ''
            ]);
        }

        Manufacturer::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/manufacturers');
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
        $allowedManufacturers = $this->getAllowedManufacturers();
        if(!$this->checkManufacturerExist($id, $allowedManufacturers)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/manufacturers');
        }

        $manufacturer = Manufacturer::find($id);
        $notifications = new notifications();
        return view('mall_manager.manufacturers.edit', ['notifications' => $notifications, 'manufacturer' => $manufacturer]);
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
        $allowedManufacturers = $this->getAllowedManufacturers();
        if(!$this->checkManufacturerExist($id, $allowedManufacturers)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/manufacturers');
        }

        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'mobile' => ['required', new PhoneNumber()],
            'email' => 'required|email',
            'address' => 'sometimes|nullable',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'website' => 'sometimes|nullable|url',
            'contact_name' => 'sometimes|nullable|string',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|image',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'manufacturers',
                'deleteFile' => Manufacturer::find($id)->icon
            ]);
        }

        Manufacturer::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/manufacturers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allowedManufacturers = $this->getAllowedManufacturers();
        if(!$this->checkManufacturerExist($id, $allowedManufacturers)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/manufacturers');
        }

        $manufacturer = Manufacturer::find($id);
        Storage::delete($manufacturer->icon);
        $manufacturer->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        $allowedManufacturers = $this->getAllowedManufacturers();
        foreach(request('delete') as $id){
            if(!$this->checkManufacturerExist($id, $allowedManufacturers)){
                session()->flash('error', trans('admin.manager_delete_or_update_error'));
                return redirect('mall-manager/manufacturers');
            }

            $manufacturer = Manufacturer::find($id);
            Storage::delete($manufacturer->icon);
            $manufacturer->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }

    private function getAllowedManufacturers(){
        $allowedManufacturers = Manufacturer::selectRaw('id as id')
                                         ->where([['owner', auth()->guard('web')->user()->id]])
                                         ->get('id');
        return $allowedManufacturers;
    }

    private function checkManufacturerExist($id, $allowedManufacturers){
        foreach ($allowedManufacturers as $key => $manufacturer) {
            if($manufacturer->id == $id){
                return true;
            }
        }
        return false;
    }
}
