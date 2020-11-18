<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ShippingsDatatable;
use App\Shipping;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class shippingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShippingsDatatable $shippings)
    {
        $notifications = new Notifications();
        return $shippings->render('admin.shippings.index', ['title' => trans('admin.shippping_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.shippings.create', ['notifications' => $notifications]);
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
            'user_id' => 'required|numeric',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|image',
        ]);

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'shippings',
                'deleteFile' => ''
            ]);
        }

        Shipping::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/shippings');
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
        $shipping = Shipping::find($id);
        $notifications = new Notifications();
        return view('admin.shippings.edit', ['shipping' => $shipping, 'notifications' => $notifications]);
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
            'user_id' => 'required|numeric',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|image',
        ]);

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'shippings',
                'deleteFile' => Shipping::find($id)->icon
            ]);
        }

        Shipping::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/shippings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipping = Shipping::find($id);
        Storage::delete($shipping->icon);
        $shipping->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach(request('delete') as $id){
            $shipping = Shipping::find($id);
            Storage::delete($shipping->icon);
            $shipping->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
