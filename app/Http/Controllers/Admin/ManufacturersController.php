<?php

namespace App\Http\Controllers\Admin;

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
        return $manufacturers->render('admin.manufacturers.index', ['title' => trans('admin.manufacturers_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.manufacturers.create', ['notifications' => $notifications]);
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

        $data['owner'] = 'admin';

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

        return redirect('admin/manufacturers');
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
        $manufacturer = Manufacturer::find($id);
        $notifications = new Notifications();
        return view('admin.manufacturers.edit', ['manufacturer' => $manufacturer, 'notifications' => $notifications]);
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

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'manufacturers',
                'deleteFile' => Manufacturer::find($id)->icon
            ]);
        }

        $manu = Manufacturer::find($id);
        $data['owner'] = $manu->owner;
        $manu->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/manufacturers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $manufacturer = Manufacturer::find($id);
        Storage::delete($manufacturer->icon);
        $manufacturer->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach(request('delete') as $id){
            $manufacturer = Manufacturer::find($id);
            Storage::delete($manufacturer->icon);
            $manufacturer->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
